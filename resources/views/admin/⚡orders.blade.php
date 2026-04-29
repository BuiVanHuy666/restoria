<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('components.layouts.admin', [
    'title' => 'Quản lý Đơn hàng',
    'heading' => 'Danh sách Đơn hàng',
    'subheading' => 'Quản lý, theo dõi và cập nhật trạng thái các đơn đặt hàng của khách.'
])]
class extends Component {
    use WithPagination;

    public ?Order $selectedOrder = null;
    public string $selectedOrderStatus = '';

    #[Computed]
    public function orders()
    {
        return Order::latest()->paginate(15);
    }

    public function viewOrder($id): void
    {
        $this->selectedOrder = Order::with('items.menuItem')->find($id);

        if ($this->selectedOrder) {
            $this->selectedOrderStatus = $this->selectedOrder->status?->value ?? '';

            \Flux::modal('order-detail-modal')->show();
        }
    }

    public function updateOrderStatus(): void
    {
        if ($this->selectedOrder) {
            $statusEnum = OrderStatus::tryFrom($this->selectedOrderStatus);

            if ($statusEnum) {
                $this->selectedOrder->update([
                    'status' => $statusEnum
                ]);

                \Flux::modal('order-detail-modal')->close();
            }
        }
    }
};
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex gap-4">
            <flux:input wire:model.live.debounce.500ms="search" icon="magnifying-glass" placeholder="Tìm mã đơn, SĐT khách..." class="w-80"/>
        </div>
        <flux:button variant="primary" icon="plus">Tạo đơn thủ công</flux:button>
    </div>

    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Mã đơn</flux:table.column>
                <flux:table.column>Khách hàng</flux:table.column>
                <flux:table.column>Tổng tiền</flux:table.column>
                <flux:table.column>Thanh toán</flux:table.column>
                <flux:table.column>Trạng thái</flux:table.column>
                <flux:table.column>Thời gian đặt</flux:table.column>
                <flux:table.column>Thao tác</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($this->orders as $order)
                    <flux:table.row wire:key="order-{{ $order->id }}">
                        <flux:table.cell class="font-medium text-zinc-900 dark:text-white cursor-pointer" wire:click="viewOrder({{ $order->id }})">
                            #{{ $order->code }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="font-medium">{{ $order->customer_name }}</div>
                            <div class="text-xs text-zinc-500">{{ $order->customer_phone }}</div>
                        </flux:table.cell>

                        <flux:table.cell class="font-medium">
                            {{ number_format($order->total_amount) }}đ
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="{{ $order->payment_status->adminColor() }}" icon="{{ $order->payment_status->adminIcon() }}" size="sm">
                                {{ $order->payment_status->label() }}
                                @if($order->payment_method === 'vnpay')
                                    <span class="ml-1 text-[10px] opacity-70">(VNPay)</span>
                                @endif
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell>
                            <flux:badge color="{{ $order->status->adminColor() }}" icon="{{ $order->status->adminIcon() }}">
                                {{ $order->status->label() }}
                            </flux:badge>
                        </flux:table.cell>

                        <flux:table.cell class="text-zinc-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </flux:table.cell>

                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button variant="ghost" size="sm" icon="eye" class="cursor-pointer" wire:click="viewOrder({{ $order->id }})"/>
                                <flux:button variant="ghost" size="sm" icon="pencil-square" href="#"/>
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="7" class="text-center py-8 text-zinc-500">
                            Chưa có đơn hàng nào trong hệ thống.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>

        <div class="mt-4">
            {{ $this->orders->links() }}
        </div>
    </flux:card>

    <flux:modal name="order-detail-modal" class="min-w-175 space-y-6">
        @if($selectedOrder)
            <div>
                <flux:heading size="lg">Chi tiết đơn hàng #{{ $selectedOrder->code }}</flux:heading>
                <flux:subheading>Thông tin chi tiết và xử lý trạng thái đơn hàng.</flux:subheading>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <div class="text-sm text-zinc-500">Khách hàng</div>
                    <div class="font-medium text-zinc-900 dark:text-white">{{ $selectedOrder->customer_name }}</div>
                    <div class="text-sm">{{ $selectedOrder->customer_phone }}</div>
                    <div class="text-sm text-zinc-500 mt-2">{{ $selectedOrder->shipping_address }}
                        , {{ $selectedOrder->shipping_ward }}, {{ $selectedOrder->shipping_province }}</div>
                </div>

                <div class="space-y-2 bg-zinc-50 dark:bg-zinc-800 p-4 rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <flux:select wire:model="selectedOrderStatus" wire:change="updateOrderStatus" label="Cập nhật trạng thái ngay">
                        @foreach(App\Enums\OrderStatus::cases() as $status)
                            <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <div class="text-xs text-zinc-500 mt-2">
                        *Lưu ý: Thay đổi trạng thái sẽ được lưu ngay lập tức vào hệ thống.
                    </div>
                </div>
            </div>

            @if($selectedOrder->note)
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-amber-800 dark:text-amber-300">Ghi chú từ khách hàng:
                            </div>
                            <div class="text-sm text-amber-700 dark:text-amber-400 mt-1 italic">{{ $selectedOrder->note }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($selectedOrder->payment_detail)
                <div x-data="{ open: false }" class="border border-zinc-200 dark:border-zinc-700 rounded-lg overflow-hidden">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800/50 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none">
                        <div class="flex items-center gap-2 font-medium text-sm text-zinc-700 dark:text-zinc-300">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Log giao dịch (Payment Details)
                        </div>
                        <svg :class="{'rotate-180': open}" class="w-4 h-4 text-zinc-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse x-cloak>
                        <div class="p-4 bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 overflow-x-auto">
                            <pre class="text-[11px] text-emerald-400 font-mono leading-relaxed"><code>{{ json_encode($selectedOrder->payment_detail, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                        </div>
                    </div>
                </div>
            @endif

            <flux:separator/>

            <div>
                <div class="font-medium mb-4">Danh sách món ăn</div>
                <div class="space-y-4">
                    @foreach($selectedOrder->items as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $item->menuItem->thumbnail_url ?? asset('images/default-food.png') }}" class="w-12 h-12 rounded object-cover" alt="Food">
                                <div>
                                    <div class="font-medium text-sm">{{ $item->menuItem->name ?? 'Món ăn đã bị xóa' }}</div>
                                    <div class="text-xs text-zinc-500">{{ number_format($item->original_price) }}đ
                                        x {{ $item->quantity }}</div>

                                    @if($item->note)
                                        <div class="text-xs text-amber-600 dark:text-amber-400 mt-1 italic flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            * {{ $item->note }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="font-medium">
                                {{ number_format($item->item_price * $item->quantity) }}đ
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <flux:separator/>

            <div class="flex justify-end">
                <div class="w-64 space-y-2 text-sm">
                    <div class="flex justify-between text-zinc-500">
                        <span>Tạm tính món:</span>
                        <span>{{ number_format($selectedOrder->subtotal) }}đ</span>
                    </div>
                    <div class="flex justify-between text-zinc-500">
                        <span>Phí giao hàng:</span>
                        <span>{{ number_format($selectedOrder->shipping_fee) }}đ</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t border-zinc-200 dark:border-zinc-700">
                        <span>Tổng cộng:</span>
                        <span class="text-emerald-600">{{ number_format($selectedOrder->total_amount) }}đ</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" x-on:click="$flux.modal('order-detail-modal').close()">Đóng</flux:button>
            </div>
        @endif
    </flux:modal>
</div>
