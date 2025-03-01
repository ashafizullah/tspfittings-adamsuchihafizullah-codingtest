@extends('apperror')

@section('content')
<div class="min-vh-100 d-flex align-items-center">
   <div class="container">
       <div class="row align-items-center justify-content-center">
           <!-- Image Column -->
           <div class="col-lg-6 col-md-8 mb-5 mb-lg-0">
               <div class="text-center">
                   <img
                       src="/images/not_found.png"
                       alt="Page Not Found Illustration"
                       class="img-fluid animation-bounce"
                       style="max-width: 450px"
                   >
               </div>
           </div>

           <!-- Content Column -->
           <div class="col-lg-6 col-md-8 text-center text-lg-start">
               <div class="error-content">
                   <!-- Error Code -->
                   <h1 class="display-1 fw-bold text-primary mb-4">404</h1>

                   <!-- Error Message -->
                   <h2 class="h3 fw-bold mb-3">Duh! Kamu mau ke mana?</h2>
                   <p class="text-muted mb-4">
                       Halaman yang kamu minta tidak ditemukan atau mungkin telah dipindahkan.
                   </p>

                   <!-- Action Buttons -->
                   <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                       <!-- Back Button -->
                       <button
                           onclick="window.history.back()"
                           class="btn btn-outline-secondary"
                       >
                           <i class="fas fa-arrow-left me-2"></i>
                           Kembali
                       </button>

                       <!-- Home Button -->
                       <a
                           href="{{ Auth::check() ? '/apps/dashboard' : '/' }}"
                           class="btn btn-primary"
                       >
                           <i class="fas fa-home me-2"></i>
                           Ke Beranda
                       </a>
                   </div>

                   <!-- Additional Help -->
                   <div class="mt-4 text-muted">
                       <p class="small mb-1">Beberapa hal yang bisa kamu coba:</p>
                       <ul class="small text-start d-inline-block">
                           <li>Periksa URL yang kamu masukkan</li>
                           <li>Refresh halaman</li>
                           <li>Kembali ke halaman sebelumnya</li>
                       </ul>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>

<style>
.animation-bounce {
   animation: bounce 2s ease infinite;
}

@keyframes bounce {
   0%, 100% {
       transform: translateY(0);
   }
   50% {
       transform: translateY(-20px);
   }
}

.error-content {
   padding: 2rem;
   border-radius: 1rem;
   background: rgba(255, 255, 255, 0.9);
   box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
}

/* Responsive adjustments */
@media (max-width: 991.98px) {
   .error-content {
       padding: 1.5rem;
       text-align: center;
   }
}

/* Optional: Add gradient background */
body {
   background: linear-gradient(135deg, #f5f7fa 0%, #e4e7eb 100%);
}

/* Improve button hover states */
.btn {
   transition: all 0.3s ease;
}

.btn:hover {
   transform: translateY(-2px);
   box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

/* Improve typography */
h1.display-1 {
   background: linear-gradient(45deg, #2196F3, #1976D2);
   -webkit-background-clip: text;
   -webkit-text-fill-color: transparent;
   font-size: 6rem;
}

/* Add subtle animation to list items */
ul li {
   transition: all 0.2s ease;
   cursor: default;
}

ul li:hover {
   transform: translateX(5px);
   color: #2196F3;
}
</style>

{{-- Optional: Add custom scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
   // Add smooth scroll behavior
   document.querySelectorAll('a[href^="#"]').forEach(anchor => {
       anchor.addEventListener('click', function (e) {
           e.preventDefault();
           document.querySelector(this.getAttribute('href')).scrollIntoView({
               behavior: 'smooth'
           });
       });
   });
});
</script>
@endsection
