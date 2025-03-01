@extends('apperror')

@section('content')
    <div class="about-section">
        <div class="auto-container">
            <div class="inner-container">
                <div class="row align-items-center clearfix">
                    <div class="image-column col-lg-6">
                        <div class="about-image">
                            <div class="about-inner-image">
                                <img src="/images/not_found.png" alt="about">
                            </div>
                        </div>
                    </div>
                    <div class="content-column col-lg-6 col-md-12 col-sm-12 mb-0">
                        <div class="about-column">
                            <div class="sec-title">
                                <div class="title">Duh! Kamu mau ke mana?</div>
                                <h2>kesalahan pada server</h2>
                            </div>
                            <div class="text">
                                <p>Halaman yang kamu minta tidak ditemukan atau mungkin telah dipindahkan.</p>
                                <p>Kembali ke <a href="/">Home</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
