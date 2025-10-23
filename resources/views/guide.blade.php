@extends('app')

@section('title', 'HÆ°á»›ng dáº«n mua vÃ©')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .layout {
        max-width: 1200px;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<main class="w-full">
    <div class="layout py-10 px-4 xl:px-0">
        
        <!-- Header -->
        <div>
            <p class="text-center text-2xl font-medium uppercase text-[#ef5222]">
                HÆ¯á»šNG DáºªN MUA VÃ‰ XE TRÃŠN WEBSITE
                <a href="{{ url('/') }}" class="ml-1 text-[#00613D]">{{ config('app.name', 'futabus.vn') }}</a>
            </p>
        </div>

        <div class="text-center">
            <!-- QR Code Section -->
            <div class="mt-10 sm:mt-[60px]">
                <p class="text-lg font-medium uppercase text-[#111111]">QUÃ‰T MÃƒ QR Táº¢I APP DÃ€NH CHO KHÃCH HÃ€NG</p>
                <div class="item mt-4 flex justify-center gap-4">
                    <img src="{{ asset('images/guideCharge/logo_futa.png') }}" alt="" width="120">
                    <img src="{{ asset('images/guideCharge/qr_app.png') }}" alt="" width="120">
                </div>
                <div class="item mt-4 flex justify-center gap-8">
                    <a target="_blank" href="http://onelink.to/futa.android" rel="noreferrer">
                        <div class="">
                            <img alt="" loading="lazy" width="86" height="28" decoding="async" data-nimg="1" 
                                class="transition-all duration-200 undefined" 
                                src="https://cdn.futabus.vn/futa-busline-cms-dev/CH_Play_712783c88a/CH_Play_712783c88a.svg" 
                                style="color: transparent;">
                        </div>
                    </a>
                    <a target="_blank" href="http://onelink.to/futa.ios" rel="noreferrer">
                        <div class="">
                            <img alt="" loading="lazy" width="86" height="28" decoding="async" data-nimg="1" 
                                class="transition-all duration-200 undefined" 
                                src="https://cdn.futabus.vn/futa-busline-cms-dev/App_Store_60da92cb12/App_Store_60da92cb12.svg" 
                                style="color: transparent;">
                        </div>
                    </a>
                </div>
            </div>

            <!-- Company Info -->
            <div class="mt-10 bg-[#FFF7F5] p-4 text-center rounded-lg">
                <p class="mt-4 text-base font-medium text-[#EF5222] sm:text-xl">UY TÃNH â€“ CHáº¤T LÆ¯á»¢NG â€“ DANH Dá»°</p>
                <div class="mt-4 flex flex-col gap-2 text-justify text-base font-medium text-[#111111] sm:text-left sm:text-lg">
                    <div>CÃ´ng Ty Cá»• pháº§n Xe KhÃ¡ch PhÆ°Æ¡ng Trang - FUTA Bus Lines xin gá»­i lá»i cáº£m Æ¡n chÃ¢n thÃ nh Ä‘áº¿n QuÃ½ KhÃ¡ch hÃ ng Ä‘Ã£ tin tÆ°á»Ÿng vÃ  sá»­ dá»¥ng dá»‹ch vá»¥ cá»§a chÃºng tÃ´i. ChÃºng tÃ´i luÃ´n hoáº¡t Ä‘á»™ng vá»›i tÃ´n chá»‰ "Cháº¥t lÆ°á»£ng lÃ  danh dá»±" vÃ  ná»— lá»±c khÃ´ng ngá»«ng Ä‘á»ƒ mang Ä‘áº¿n tráº£i nghiá»‡m dá»‹ch vá»¥ tá»‘i Æ°u dÃ nh cho KhÃ¡ch hÃ ng.</div>
                    <div>ChÃºng tÃ´i khÃ´ng chá»‰ Ä‘áº£m báº£o cÃ¡c chuyáº¿n xe an toÃ n, cháº¥t lÆ°á»£ng vÃ  Ä‘Ãºng háº¹n, mÃ  cÃ²n chÃº trá»ng Ä‘áº¿n tráº£i nghiá»‡m mua vÃ© cá»§a KhÃ¡ch hÃ ng. ChÃºng tÃ´i Ä‘Ã£ cáº£i tiáº¿n website mua vÃ© trá»±c tuyáº¿n Ä‘á»ƒ Ä‘áº£m báº£o viá»‡c mua vÃ© dá»… dÃ ng vÃ  tiá»‡n lá»£i hÆ¡n bao giá» háº¿t.</div>
                    <div>BÃªn cáº¡nh Ä‘Ã³, chÃºng tÃ´i tá»± hÃ o giá»›i thiá»‡u á»©ng dá»¥ng mua vÃ© FUTA Bus, giÃºp KhÃ¡ch hÃ ng tiáº¿t kiá»‡m thá»i gian mua vÃ©. Qua á»©ng dá»¥ng nÃ y, KhÃ¡ch hÃ ng cÃ³ thá»ƒ tra cá»©u thÃ´ng tin vá» lá»‹ch trÃ¬nh, chá»n gháº¿/giÆ°á»ng vÃ  thanh toÃ¡n nhanh chÃ³ng, thuáº­n tiá»‡n trÃªn Ä‘iá»‡n thoáº¡i di Ä‘á»™ng.</div>
                </div>
            </div>
        </div>

        <!-- Step 1: Features -->
        <div class="p-0 sm:p-5">
            <div class="mt-8 text-center text-2xl font-bold text-[#00613D] sm:text-3xl">
                BÆ°á»›c 1: Nhá»¯ng tráº£i nghiá»‡m ná»•i báº­t mÃ  á»¨ng Dá»¥ng Mua VÃ© 
                <span class="text-[#EF5222]"> FUTA Bus</span> 
                vÃ  Website
                <span class="text-[#EF5222]"> futabus.vn</span> 
                mang láº¡i
            </div>
            
            <div class="mt-[50px] grid grid-cols-3 gap-0 sm:gap-5">
                <!-- Feature 1 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/time.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        KhÃ¡ch hÃ ng chá»§ Ä‘á»™ng vá» lá»‹ch trÃ¬nh cá»§a mÃ¬nh: Tá»« Ä‘iá»ƒm Ä‘Ã³n, Ä‘iá»ƒm tráº£ khÃ¡ch Ä‘áº¿n thá»i gian hÃ nh trÃ¬nh.
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/chair.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        KhÃ¡ch hÃ ng Ä‘Æ°á»£c chá»n vÃ  chá»§ Ä‘á»™ng vá»‹ trÃ­, sá»‘ gháº¿ ngá»“i trÃªn xe.
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/comfortable.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        KhÃ´ng pháº£i xáº¿p hÃ ng nhá»¯ng dá»‹p Lá»…, Táº¿t.
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/ulity.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        Dá»… dÃ ng káº¿t há»£p vÃ  nháº­n Æ°u Ä‘Ã£i khi sá»­ dá»¥ng dá»‹ch vá»¥ khÃ¡c cá»§a PhÆ°Æ¡ng Trang nhÆ° Taxi, Tráº¡m Dá»«ng, Váº­n Chuyá»ƒn HÃ ng HoÃ¡...
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/interest.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        Khi Ä‘Äƒng kÃ½ thÃ nh viÃªn, khÃ¡ch hÃ ng cÃ²n nháº­n nhiá»u Æ°u Ä‘Ã£i, cÅ©ng nhÆ° nhiá»u pháº§n quÃ  háº¥p dáº«n.
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-span-3 mt-3 bg-[#ffffff] p-8 text-center sm:col-span-1" 
                    style="box-shadow:0px 4px 32px rgba(0, 0, 0, 0.1);border-radius:16px">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/guideCharge/web/step1/comment.svg') }}" alt="">
                    </div>
                    <div class="mt-5 text-lg font-medium text-[#111111]">
                        Dá»… dÃ ng gÃ³p Ã½ Ä‘á»ƒ nÃ¢ng cao cháº¥t lÆ°á»£ng dá»‹ch vá»¥.
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Process -->
        <div class="mt-10 bg-[#FFF7F5] p-5 rounded-lg">
            <div class="mt-8 text-center text-2xl font-bold text-[#00613D] sm:text-3xl">
                BÆ°á»›c 2: Nhá»¯ng bÆ°á»›c Ä‘á»ƒ giÃºp khÃ¡ch hÃ ng tráº£i nghiá»‡m mua vÃ© nhanh
            </div>

            <!-- Progress Indicator 1 -->
            <div class="no-scrollbar mt-10 block justify-center overflow-x-auto sm:flex sm:overflow-hidden">
                <div class="mb-20 flex w-max items-center justify-center gap-4 sm:w-auto">
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_active.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#EF5222]">01</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Truy cáº­p vÃ o Ä‘á»‹a chá»‰ futabus.vn
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">02</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n thÃ´ng tin hÃ nh trÃ¬nh
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">03</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">04</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">05</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Mua vÃ© xe thÃ nh cÃ´ng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 1 Detail -->
            <div class="mt-10 text-center text-2xl font-semibold text-[#111111] sm:mt-[50px] sm:text-3xl">
                BÆ°á»›c 1: Truy cáº­p Ä‘á»‹a chá»‰
                <a href="{{ url('/') }}" class="ml-2 text-[#EF5222]">{{ config('app.name', 'futabus.vn') }}</a>
            </div>
            <img src="{{ asset('images/guideCharge/web/step1/step1.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">

            <!-- App Download -->
            <div class="mt-[40px] text-center text-2xl font-semibold text-[#111111]">
                Táº£i á»©ng dá»¥ng táº¡i 
                <a class="text-[#EF5222]" href="{{ url('/') }}">{{ config('app.name', 'futabus.vn') }}</a> 
                hoáº·c tÃ¬m á»©ng dá»¥ng Futa Bus trÃªn<br>
                Futa Bus trÃªn<span class="text-[#EF5222]"> Google Play</span> 
                hoáº·c<span class="text-[#EF5222]"> Apple store</span>
            </div>
            <div class="item mt-4 flex justify-center gap-8">
                <a target="_blank" href="http://onelink.to/futa.android" rel="noreferrer">
                    <div class="">
                        <img alt="" loading="lazy" width="86" height="28" decoding="async" data-nimg="1" 
                            class="transition-all duration-200 undefined" 
                            src="https://cdn.futabus.vn/futa-busline-cms-dev/CH_Play_712783c88a/CH_Play_712783c88a.svg" 
                            style="color: transparent;">
                    </div>
                </a>
                <a target="_blank" href="http://onelink.to/futa.ios" rel="noreferrer">
                    <div class="">
                        <img alt="" loading="lazy" width="86" height="28" decoding="async" data-nimg="1" 
                            class="transition-all duration-200 undefined" 
                            src="https://cdn.futabus.vn/futa-busline-cms-dev/App_Store_60da92cb12/App_Store_60da92cb12.svg" 
                            style="color: transparent;">
                    </div>
                </a>
            </div>

            <!-- Progress Indicator 2 -->
            <div class="no-scrollbar mt-10 block justify-center overflow-x-auto sm:flex sm:overflow-hidden">
                <div class="mb-20 flex w-max items-center justify-center gap-4 sm:w-auto">
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">01</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Truy cáº­p vÃ o Ä‘á»‹a chá»‰ futabus.vn
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_active.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#EF5222]">02</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n thÃ´ng tin hÃ nh trÃ¬nh
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">03</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">04</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">05</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Mua vÃ© xe thÃ nh cÃ´ng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Detail Part 1 -->
            <div class="mt-10 text-center text-2xl font-semibold text-[#111111] sm:mt-[50px] sm:text-3xl"></div>
            <img src="{{ asset('images/guideCharge/web/step1/step2_1.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">
            
            <div class="m-auto mt-10">
                <div class="m-auto grid w-[90%] grid-cols-2 gap-4">
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">1</div>
                        <div class="text-2xl font-medium text-[#111111]">Chá»n Ä‘iá»ƒm khá»Ÿi hÃ nh</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">2</div>
                        <div class="text-2xl font-medium text-[#111111]">Chá»n Ä‘iá»ƒm Ä‘áº¿n</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">3</div>
                        <div class="text-2xl font-medium text-[#111111]">Chá»n ngÃ y Ä‘i</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">4</div>
                        <div class="text-2xl font-medium text-[#111111]">Chá»n ngÃ y vá»</div>
                    </div>
                </div>
            </div>

            <!-- Step 2 Detail Part 2 -->
            <img src="{{ asset('images/guideCharge/web/step1/step2_2.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">
            
            <div class="m-auto mt-10">
                <div class="m-auto grid w-[90%] grid-cols-2 gap-4">
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">1</div>
                        <div class="text-[26px] font-medium text-[#111111]">Chá»n giá» Ä‘i</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">2</div>
                        <div class="text-[26px] font-medium text-[#111111]">Chá»n loáº¡i xe</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">3</div>
                        <div class="text-[26px] font-medium text-[#111111]">Chá»n Ä‘iá»ƒm Ä‘Ã³n</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">4</div>
                        <div class="text-[26px] font-medium text-[#111111]">Chá»n chuyáº¿n Ä‘i</div>
                    </div>
                    <div class="col-span-2 mt-4 flex items-center sm:col-span-1">
                        <div class="mr-4 flex h-[63px] w-[63px] items-center justify-center rounded-full text-[45px] font-medium text-[#F2754E]" 
                            style="border:2px dashed #F2754E">5</div>
                        <div class="text-[26px] font-medium text-[#111111]">Chá»n nhanh sá»‘ gháº¿</div>
                    </div>
                </div>
            </div>

            <!-- Progress Indicator 3 -->
            <div class="no-scrollbar mt-10 block justify-center overflow-x-auto sm:flex sm:overflow-hidden">
                <div class="mb-20 flex w-max items-center justify-center gap-4 sm:w-auto">
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">01</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Truy cáº­p vÃ o Ä‘á»‹a chá»‰ futabus.vn
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">02</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n thÃ´ng tin hÃ nh trÃ¬nh
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_active.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#EF5222]">03</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">04</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">05</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Mua vÃ© xe thÃ nh cÃ´ng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3 Detail -->
            <div class="mt-10 text-center text-2xl font-semibold text-[#111111] sm:mt-[50px] sm:text-3xl">
                BÆ°á»›c 3: Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
            </div>
            <img src="{{ asset('images/guideCharge/web/step1/step3.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">

            <!-- Progress Indicator 4 -->
            <div class="no-scrollbar mt-10 block justify-center overflow-x-auto sm:flex sm:overflow-hidden">
                <div class="mb-20 flex w-max items-center justify-center gap-4 sm:w-auto">
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">01</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Truy cáº­p vÃ o Ä‘á»‹a chá»‰ futabus.vn
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">02</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n thÃ´ng tin hÃ nh trÃ¬nh
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">03</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_active.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#EF5222]">04</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">05</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Mua vÃ© xe thÃ nh cÃ´ng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 4 Detail -->
            <div class="mt-10 text-center text-2xl font-semibold text-[#111111] sm:mt-[50px] sm:text-3xl">
                BÆ°á»›c 4: Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
            </div>
            <img src="{{ asset('images/guideCharge/web/step1/step4.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">

            <!-- Progress Indicator 5 -->
            <div class="no-scrollbar mt-10 block justify-center overflow-x-auto sm:flex sm:overflow-hidden">
                <div class="mb-20 flex w-max items-center justify-center gap-4 sm:w-auto">
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">01</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Truy cáº­p vÃ o Ä‘á»‹a chá»‰ futabus.vn
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">02</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n thÃ´ng tin hÃ nh trÃ¬nh
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">03</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n gháº¿, Ä‘iá»ƒm Ä‘Ã³n tráº£, thÃ´ng tin hÃ nh khÃ¡ch
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_inactive.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#C1C1CC]">04</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Chá»n phÆ°Æ¡ng thá»©c thanh toÃ¡n
                            </div>
                            <img src="{{ asset('images/guideCharge/web/step1/step_progress.svg') }}" alt="" class="ml-2 w-8 lg:w-auto">
                        </div>
                    </div>
                    <div>
                        <div class="relative flex items-center">
                            <img src="{{ asset('images/guideCharge/web/step1/bound_step_active.png') }}" alt="" class="w-[80px] lg:w-[118px]">
                            <p class="absolute left-6 text-2xl font-extrabold lg:left-[34px] lg:text-[40px] text-[#EF5222]">05</p>
                            <div class="absolute flex h-[80px] w-auto items-center text-sm text-[#000000] lg:w-[150px] lg:text-lg" style="left:0px;bottom:-80px">
                                Mua vÃ© xe thÃ nh cÃ´ng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 5 Detail -->
            <div class="mt-10 text-center text-2xl font-semibold text-[#111111] sm:mt-[50px] sm:text-3xl">
                BÆ°á»›c 5: Mua vÃ© thÃ nh cÃ´ng
            </div>
            <img src="{{ asset('images/guideCharge/web/step1/step5.png') }}" alt="" class="m-auto mt-10 rounded-lg shadow-lg max-w-full">
        </div>

        <!-- Email Confirmation -->
        <div class="p-5">
            <div class="mt-8 text-center text-2xl font-bold text-[#00613D] sm:text-3xl">
                BÆ°á»›c 3: VÃ© xe sáº½ Ä‘Æ°á»£c gá»­i vá» Email. QuÃ½ khÃ¡ch vui lÃ²ng kiá»ƒm tra Email Ä‘á»ƒ nháº­n vÃ©
            </div>
            <div class="mt-[50px]">
                <img src="{{ asset('images/guideCharge/web/step1/co_step3.PNG') }}" alt="" class="m-auto mt-10 w-full rounded-lg shadow-lg" style="max-width: 800px;">
            </div>
        </div>

    </div>
</main>
@endsection
