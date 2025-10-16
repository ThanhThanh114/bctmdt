<!DOCTYPE html>
<script src="https://cdn.tailwindcss.com?v=<?= time() ?>"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'green': '#059669',
                    'orange': '#ea5a47',
                    'gray': '#6b7280'
                }
            }
        }
    }
</script>

<!-- FOOTER UPDATED: <?= date('Y-m-d H:i:s') ?> -->
<footer class="mx-auto w-full bg-[#FFF7F5] text-[15px] block"
    style="background-color: #FFF7F5 !important; font-size: 15px !important;">
    <div class="p-4 sm:pt-12">
        <div class="max-w-6xl mx-auto flex flex-wrap gap-4 font-normal text-black lg:gap-14">
            <div class="flex max-w-[34rem] flex-col">
                <div class="flex w-full max-w-md justify-between">
                    <div class="">
                        <p class="text-green font-medium uppercase">TRUNG TÂM TỔNG ĐÀI & CSKH</p>
                        <a href="tel:19006067" class="text-3xl font-medium text-orange">1900 6067</a>
                    </div>
                    <a target="_blank" href="http://online.gov.vn/Home/WebDetails/14029">
                        <div class="relative h-[60px] w-40">
                            <img alt="" loading="lazy" decoding="async"
                                class="transition-all duration-200 relative h-[60px] w-40"
                                src="https://cdn.futabus.vn/futa-busline-cms-dev/logo_Sale_Noti_7dab1d54a1/logo_Sale_Noti_7dab1d54a1.png"
                                style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
                        </div>
                    </a>
                </div>

                <span class="text-green mt-5 font-medium uppercase">Công ty cổ phần xe khách Phương Trang - FUTA Bus
                    Lines</span>

                <span class="text-gray mt-2 sm:mt-1">
                    <span class="mr-1 min-w-fit">Địa chỉ:</span>
                    <span class="text-black">486-486A Lê Văn Lương, Phường Tân Hưng,TPHCM, Việt Nam.</span>
                </span>

                <span class="text-gray mt-2 sm:mt-1">
                    Email: <a href="mailto:hotro@futa.vn" class="text-orange">hotro@futa.vn</a>
                </span>

                <div class="mt-[10px] flex w-full max-w-md justify-between sm:mt-1">
                    <span class="text-gray">Điện thoại: <a href="tel:02838386852"
                            class="text-black">02838386852</a></span>
                    <span class="text-gray mr-px">Fax: <a href="tel:02838386853"
                            class="text-black">02838386853</a></span>
                </div>

                <div class="mb-4 mt-5 flex w-full max-w-md justify-between">
                    <div class="text-green font-medium uppercase">
                        Tải app FUTA
                        <div class="mt-2 flex gap-4">
                            <a target="_blank" class="relative" href="http://onelink.to/futa.android">
                                <div class="relative min-h-[24px] w-[86px] object-cover">
                                    <img alt="" loading="lazy" decoding="async"
                                        class="transition-all duration-200 relative min-h-[24px] w-[86px] object-cover"
                                        src="https://cdn.futabus.vn/futa-busline-cms-dev/CH_Play_712783c88a/CH_Play_712783c88a.svg"
                                        style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
                                </div>
                            </a>
                            <a target="_blank" class="relative" href="http://onelink.to/futa.ios">
                                <div class="relative min-h-[24px] w-[86px] object-cover">
                                    <img alt="" loading="lazy" decoding="async"
                                        class="transition-all duration-200 relative min-h-[24px] w-[86px] object-cover"
                                        src="https://cdn.futabus.vn/futa-busline-cms-dev/App_Store_60da92cb12/App_Store_60da92cb12.svg"
                                        style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="text-green font-medium uppercase">
                        Kết nối chúng tôi
                        <div class="mt-2 flex gap-4">
                            <a target="_blank" href="https://www.facebook.com/xephuongtrang" rel="noreferrer">
                                <div class="">
                                    <img alt="" loading="lazy" width="27" height="27" decoding="async"
                                        class="transition-all duration-200"
                                        src="https://cdn.futabus.vn/futa-busline-cms-dev/facebook_1830e1b97c/facebook_1830e1b97c.svg"
                                        style="color: transparent;">
                                </div>
                            </a>
                            <a target="_blank"
                                href="https://www.youtube.com/channel/UCs32uT002InnxFnfXCRN48A?view_as=subscriber"
                                rel="noreferrer">
                                <div class="">
                                    <img alt="" loading="lazy" width="27" height="27" decoding="async"
                                        class="transition-all duration-200"
                                        src="https://cdn.futabus.vn/futa-busline-cms-dev/youtube_d5ef476c0e/youtube_d5ef476c0e.svg"
                                        style="color: transparent;">
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden max-w-lg flex-col gap-4 sm:flex sm:flex-row">
                <div class="flex flex-col">
                    <div class="text-green h-4 font-medium">FUTA Bus Lines</div>
                    <div class="mt-1 flex max-w-md flex-col">
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="/BC_TMDT/legacy/About.php" target="_self" rel="noreferrer">Về chúng tôi</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="/BC_TMDT/legacy/LichTrinh.php" target="_self" rel="noreferrer">Lịch trình</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="https://vieclam.futabus.vn/" target="_blank" rel="noreferrer">Tuyển dụng</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="/BC_TMDT/legacy/TinTuc.php" target="_self" rel="noreferrer">Tin tức & Sự kiện</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="/BC_TMDT/legacy/LienHe.php" target="_self" rel="noreferrer">Mạng lưới văn phòng</a>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="text-green h-4 font-medium">Hỗ trợ</div>
                    <div class="mt-1 flex max-w-md flex-col">
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="/BC_TMDT/legacy/TraCuu.php" target="_self" rel="noreferrer">Tra cứu thông tin đặt
                                vé</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="#" target="_self" rel="noreferrer">Điều khoản sử dụng</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="#" target="_self" rel="noreferrer">Câu hỏi thường gặp</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="#" target="_self" rel="noreferrer">Hướng dẫn đặt vé trên Web</a>
                        </div>
                        <div class="mt-3 flex min-w-[140px] max-w-[200px] items-center">
                            <div class="border-rad mr-3 h-2 w-2 rounded-full bg-gray-300"></div>
                            <a href="#" target="_self" rel="noreferrer">Hướng dẫn nạp tiền trên App</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto mb-6 grid grid-cols-2 gap-6 sm:mb-10 sm:flex sm:flex-wrap sm:justify-center px-4">
        <a target="_blank" class="flex-1" href="https://futabus.vn/">
            <div class="aspect-[6/1] relative w-full">
                <img alt="" loading="lazy" decoding="async"
                    class="transition-all duration-200 aspect-[6/1] relative w-full"
                    src="https://cdn.futabus.vn/futa-busline-cms-dev/Bus_Lines_817c989817/Bus_Lines_817c989817.svg"
                    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            </div>
        </a>
        <a target="_blank" class="flex-1" href="https://futaexpress.vn">
            <div class="aspect-[6/1] relative w-full">
                <img alt="" loading="lazy" decoding="async"
                    class="transition-all duration-200 aspect-[6/1] relative w-full"
                    src="https://cdn.futabus.vn/futa-busline-cms-dev/logo_futa_express_0ad93b22d3/logo_futa_express_0ad93b22d3.svg"
                    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            </div>
        </a>
        <a target="_blank" class="flex-1" href="https://futaads.vn/">
            <div class="aspect-[6/1] relative w-full">
                <img alt="" loading="lazy" decoding="async"
                    class="transition-all duration-200 aspect-[6/1] relative w-full"
                    src="https://cdn.futabus.vn/futa-busline-cms-dev/FUTA_Advertising_d0b60b3a45/FUTA_Advertising_d0b60b3a45.svg"
                    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            </div>
        </a>
        <a target="_blank" class="flex-1" href="https://futabus.vn/tin-tuc/tram-dung-chan-5-sao">
            <div class="aspect-[6/1] relative w-full">
                <img alt="" loading="lazy" decoding="async"
                    class="transition-all duration-200 aspect-[6/1] relative w-full"
                    src="https://cdn.futabus.vn/futa-busline-web-cms-prod/Tdcpl_1_5d2e395adc/Tdcpl_1_5d2e395adc.png"
                    style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
            </div>
        </a>
    </div>

    <div
        class="flex min-h-[40px] flex-col items-center justify-center bg-[#00613D] py-2 text-center text-white sm:flex-row">
        <span>© 2025<span class="mx-2">|</span>Bản quyền thuộc về Công ty Cổ Phần Xe khách Phương Trang - FUTA Bus
            Lines</span>
        <span class="mx-2 hidden sm:block">|</span>
        <span>Chịu trách nhiệm quản lý nội dung: Ông Võ Duy Thành</span>
    </div>
</footer>

<!-- FUTA Chat Widget - Clean File Version -->
<script src="/assets/js/futa-chat.js"></script>
<script>
    // Initialize the chat widget when DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        console.log('🚀 Initializing FUTA Chat Widget...');

        // Create and initialize the widget
        if (typeof FUTAChatWidget !== 'undefined') {
            new FUTAChatWidget();
            console.log('✅ FUTA Chat Widget loaded successfully!');
        } else {
            console.error('❌ FUTAChatWidget class not found!');
        }
    });
</script>
