<?php

use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Ensure roles exist
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'member', 'guard_name' => 'web']);
});

test('member can access dashboard but is blocked from products and admin page', function () {
    $member = User::factory()->create();
    $member->assignRole('member');

    // Dashboard -> 200
    $this->actingAs($member)->get('/dashboard')->assertStatus(200);

    // Products -> 403 Forbidden
    $this->actingAs($member)->get('/products')->assertStatus(403);
    $this->actingAs($member)->get('/products/create')->assertStatus(403);

    // Admin Roles -> 403 Forbidden
    $this->actingAs($member)->get('/admin/roles')->assertStatus(403);
});

test('admin can access dashboard and products but is blocked from admin page', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Dashboard -> 200
    $this->actingAs($admin)->get('/dashboard')->assertStatus(200);

    // Products -> 200 OK
    $this->actingAs($admin)->get('/products')->assertStatus(200);
    $this->actingAs($admin)->get('/products/create')->assertStatus(200);

    // Admin Roles -> 403 Forbidden
    $this->actingAs($admin)->get('/admin/roles')->assertStatus(403);
});

test('super-admin can access dashboard, products, and admin page', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    // Dashboard -> 200
    $this->actingAs($superAdmin)->get('/dashboard')->assertStatus(200);

    // Products -> 200 OK
    $this->actingAs($superAdmin)->get('/products')->assertStatus(200);

    // Admin Roles -> 200 OK
    $this->actingAs($superAdmin)->get('/admin/roles')->assertStatus(200);
});

test('product CRUD operations work for authorized users', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Store product
    $storeResponse = $this->actingAs($admin)->post('/products', [
        'name' => 'Test Product',
        'category' => 'Testing',
        'price' => 150000,
        'stock' => 10,
        'description' => 'Test description',
    ]);
    $storeResponse->assertRedirect('/products');
    $this->assertDatabaseHas('products', ['name' => 'Test Product']);

    $product = Product::where('name', 'Test Product')->first();

    // Edit product
    $this->actingAs($admin)->get("/products/{$product->id}/edit")->assertStatus(200);

    // Update product
    $updateResponse = $this->actingAs($admin)->put("/products/{$product->id}", [
        'name' => 'Updated Test Product',
        'category' => 'Testing',
        'price' => 175000,
        'stock' => 20,
        'description' => 'Updated description',
    ]);
    $updateResponse->assertRedirect('/products');
    $this->assertDatabaseHas('products', ['name' => 'Updated Test Product']);

    // Destroy product
    $deleteResponse = $this->actingAs($admin)->delete("/products/{$product->id}");
    $deleteResponse->assertRedirect('/products');
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('super-admin can update user role', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $targetUser = User::factory()->create();
    $targetUser->assignRole('member');

    $response = $this->actingAs($superAdmin)->patch("/admin/users/{$targetUser->id}/role", [
        'role' => 'admin',
    ]);

    $response->assertRedirect('/admin/roles');
    expect($targetUser->fresh()->hasRole('admin'))->toBeTrue();
});
