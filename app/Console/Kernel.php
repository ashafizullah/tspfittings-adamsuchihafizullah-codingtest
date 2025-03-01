<?php

namespace App\Console;

use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\DingtalkService;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->call(function () {
			// Get all users who haven't submitted their KPI report after 06.00 PM
			$now = now();
			if (!$now->isWeekend()) {
				$users = User::whereHas('roles', function ($query) {
					$query->where('name', '!=', 'admin');
				})->whereDoesntHave('kpi_reports', function ($query) {
					$query->where('created_at', '>=', now()->startOfDay()->setTime(18, 0, 0));
				})->get();

				if ($users->count() > 0) {
					$message = "Nama di bawah ini adalah yang belum melakukan KPI Report hari ini tanggal " . now()->format('d-m-Y') . " setelah pukul 18.00 WIB:\n\n";
					foreach ($users as $user) {
						$message .= "- {$user->name}\n";
					}
					$message .= "\nTotal: {$users->count()} nama. Mohon untuk diingatkan, terima kasih.";

					$dingtalkService = new DingtalkService();

					$dingtalkService->sendNotification($message);
				} else {
					$message = "Semua karyawan telah melakukan KPI Report hari ini tanggal " . now()->format('d-m-Y') . " setelah pukul 18.00 WIB. Terima kasih.";
					$dingtalkService = new DingtalkService();

					$dingtalkService->sendNotification($message);
				}
			}
		})->dailyAt('18:00');
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
