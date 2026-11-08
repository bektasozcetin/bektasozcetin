<?php

/*
| Kod vitrini örnekleri. Her satır: [metin, vurgulu mu?]
| Açıklamalar lang/{tr,en}/site.php içinde: site.code.samples.{anahtar}
*/

return [
    'action' => [
        'label' => 'Action',
        'file' => 'app/Actions/PlaceOrder.php',
        'lines' => [
            ['// app/Actions/PlaceOrder.php'],
            ['final class PlaceOrder', true],
            ['{'],
            ['    public function handle(Cart $cart, User $user): Order'],
            ['    {'],
            ['        return DB::transaction(function () use ($cart, $user) {'],
            ['            $order = $user->orders()->create($cart->toOrderData());'],
            ['            $cart->reserveStock();'],
            ['            ProcessPayment::dispatch($order)->afterCommit();', true],
            [''],
            ['            return $order;'],
            ['        });'],
            ['    }'],
            ['}'],
        ],
    ],
    'livewire' => [
        'label' => 'Livewire',
        'file' => 'app/Livewire/ContactForm.php',
        'lines' => [
            ['// app/Livewire/ContactForm.php'],
            ['class ContactForm extends Component', true],
            ['{'],
            ["    #[Validate('required|email:rfc,dns')]", true],
            ["    public string \$email = '';"],
            [''],
            ["    #[Validate('accepted')]", true],
            ['    public bool $kvkk = false;'],
            [''],
            ['    public function send(): void'],
            ['    {'],
            ['        $this->validate();'],
            ["        Mail::to(config('mail.owner'))->queue(new CallRequested(\$this->all()));"],
            ["        \$this->dispatch('sent');"],
            ['    }'],
            ['}'],
        ],
    ],
    'pest' => [
        'label' => 'Pest',
        'file' => 'tests/Feature/PlaceOrderTest.php',
        'lines' => [
            ['// tests/Feature/PlaceOrderTest.php'],
            ["it('reserves stock and queues payment', function () {", true],
            ['    Queue::fake();'],
            ['    $cart = Cart::factory()->withItems(3)->create();'],
            [''],
            ['    $order = app(PlaceOrder::class)->handle($cart, $cart->user);'],
            [''],
            ['    expect($order->items)->toHaveCount(3);', true],
            ['    Queue::assertPushed(ProcessPayment::class);', true],
            ['});'],
        ],
    ],
    'migration' => [
        'label' => 'Migration',
        'file' => 'database/migrations/create_orders_table.php',
        'lines' => [
            ['// database/migrations/create_orders_table.php'],
            ["Schema::create('orders', function (Blueprint \$table) {", true],
            ['    $table->id();'],
            ["    \$table->foreignId('user_id')->constrained()->cascadeOnDelete();"],
            ["    \$table->unsignedInteger('total'); // kuruş", true],
            ["    \$table->string('status')->default('pending')->index();", true],
            ['    $table->timestamps();'],
            ['});'],
        ],
    ],
];
