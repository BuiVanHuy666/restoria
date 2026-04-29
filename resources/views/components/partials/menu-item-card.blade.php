@props(['item', 'index' => 0])

@php
    $delay = ($index % 3) * 200;

    $isSpecialBox = !$item->is_round_image;
    $isAvailable = $item->isAvailable();
@endphp

<div class="offer-block-three col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
    <div class="inner-box {{ $isSpecialBox ? 'special-box' : '' }} wow fadeInUp" data-wow-duration="1500ms" data-wow-delay="{{ $delay }}ms">

        <div class="image relative">
                <img src="{{ $item->thumbnailUrl ?? asset('images/resource/default_food.jpg') }}"
                     alt="{{ $item->name }}"
                     class="object-cover h-64 w-full {{ $item->is_round_image ? 'rounded-full' : '' }}"
                     loading="lazy"
                >

            <div class="absolute z-100 flex flex-col items-center" style="bottom: -15px; left: 50%; transform: translateX(-50%); width: 100%;">
                @if(!$isAvailable)
                    <span class="special-tag" style="position: relative; bottom: auto; left: auto; transform: none; background-color: #6b7280; color: white;">
                        Hết hàng
                    </span>
                @else
                    @if($item->has_discount)
                        <span class="special-tag" style="position: relative; bottom: auto; left: auto; transform: none; background-color: #ef4444;">
                            Khuyến mãi
                        </span>
                    @endif
                    @if($item->is_new)
                        <span class="special-tag" style="position: relative; bottom: auto; left: auto; transform: none; background-color: #10b981;">
                            Món mới
                        </span>
                    @endif
                    @if($item->is_popular)
                        <span class="special-tag" style="position: relative; bottom: auto; left: auto; transform: none; background-color: #d2a349;">
                            Bán chạy
                        </span>
                    @endif
                @endif
            </div>
        </div>

        <h5><a href="#">{{ $item->name }}</a></h5>
        <div class="text desc">{{ $item->description ?? 'Đang cập nhật mô tả món ăn...' }}</div>

        <div class="price flex items-center justify-center gap-2 mt-3 mb-2 {{ !$isAvailable ? 'opacity-50' : '' }}">
            @if($item->has_discount)
                <span class="font-bold text-[#10b981] text-xl">
                    {{ number_format($item->discounted_price) }}đ
                </span>
                <span class="text-gray-400 line-through text-base font-normal">
                    {{ number_format($item->price) }}đ
                </span>
            @else
                <span class="font-bold text-xl">
                    {{ number_format($item->price) }}đ
                </span>
            @endif
        </div>

        @if($isAvailable)
            <livewire:customer.add-to-cart wire:key="cart-{{ $item->id }}" :product-id="$item->id"/>
        @else
            <div class="text-zinc-500 font-medium text-sm mt-3 py-2 border border-zinc-200 bg-zinc-50 rounded-lg text-center cursor-not-allowed">
                Tạm hết hàng
            </div>
        @endif
    </div>
</div>
