<!-- Map section (bus companies location) -->
<div class="row mt-3">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-map-marked-alt mr-2"></i>Vị trí các nhà xe trên bản đồ</span>
                <div>
                    <button class="btn btn-sm btn-light"><i class="fas fa-search-location mr-1"></i>Tổng:
                        {{ $stats['total_bus_companies'] ?? 15 }} nhà xe</button>
                    <button class="btn btn-sm btn-info ml-2"><i class="fas fa-search-plus mr-1"></i>Zoom để xem chi
                        tiết</button>
                </div>
            </div>
            <div class="card-body p-0" style="height: 400px; overflow: hidden; position: relative;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125423.2745799879!2d106.57958515!3d10.823098950000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175284f2ce0866d%3A0x2a4c4b3d34c0f58d!2zSG8gQ2hpIE1pbmggQ2l0eSwgVmlldG5hbQ!5e0!3m2!1sen!2s!4v1634234567890!5m2!1sen!2s"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                <div
                    style="position: absolute; bottom: 10px; left: 10px; background: rgba(255,255,255,0.9); padding: 8px 12px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <small><strong>Hiện thị vị trí 15 nhà xe đối tác</strong></small>
                </div>
            </div>
        </div>
    </div>
</div>
