<?php

use App\Filament\Resources\FeedbackResource;
use App\Models\Feedback;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\UserRoleSeeder;
use Filament\Pages\Actions\DeleteAction;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\get;
use function Pest\Laravel\seed;
use function Pest\Livewire\livewire;

beforeEach(function () {
    seed([
        PermissionSeeder::class,
        UserRoleSeeder::class,
    ]);
    actingAs(User::factory()->superAdmin()->create());
});

it('should render feedback index page', function () {
    get(FeedbackResource::getUrl('index'))
        ->assertSuccessful();
});

it('should render list feedback component ', function () {
    $feedbacks = Feedback::factory()->count(10)->create();

    livewire(FeedbackResource\Pages\ListFeedback::class)
        ->assertCanSeeTableRecords($feedbacks);
});

it('should render feedback create page', function () {
    get(FeedbackResource::getUrl('create'))
        ->assertSuccessful();
});

it('should create feedback', function () {
    $feedback = Feedback::factory()->make();
    livewire(FeedbackResource\Pages\CreateFeedback::class)
        ->fillForm([
            'content' => $feedback->content,
            'name' => $feedback->name,
            'is_listed' => $feedback->is_listed,
            'user_id' => $feedback->user_id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
});

it('can validate input', function () {
    livewire(FeedbackResource\Pages\CreateFeedback::class)
        ->fillForm([
        ])
        ->call('create')
        ->assertHasFormErrors([
            'content',
        ]);
});

it('should render feedback view page', function () {
    get(FeedbackResource::getUrl('view', [
        'record' => Feedback::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to view feedback record ', function () {
    $feedback = Feedback::factory()->create();

    livewire(FeedbackResource\Pages\ViewFeedback::class, [
        'record' => $feedback->getKey(),
    ])
        ->assertFormSet([
            'content' => $feedback->content,
            'name' => $feedback->name,
            'is_listed' => $feedback->is_listed,
            'user_id' => $feedback->user_id,
        ]);
});

it('should render feedback edit page', function () {
    get(FeedbackResource::getUrl('edit', [
        'record' => Feedback::factory()->create(),
    ]))->assertSuccessful();
});

it('should render page to show feedback record in edit view', function () {
    $feedback = Feedback::factory()->create();

    livewire(FeedbackResource\Pages\EditFeedback::class, [
        'record' => $feedback->getKey(),
    ])
        ->assertFormSet([
            'content' => $feedback->content,
            'name' => $feedback->name,
            'is_listed' => $feedback->is_listed,
            'user_id' => $feedback->user_id,
        ]);
});

it('should edit feedback', function () {
    $feedback = Feedback::factory()->create();
    $newTour = Feedback::factory()->make();

    livewire(FeedbackResource\Pages\EditFeedback::class, [
        'record' => $feedback->getKey(),
    ])
        ->fillForm([
            'content' => $newTour->content,
            'name' => $newTour->name,
            'is_listed' => $newTour->is_listed,
            'user_id' => $newTour->user_id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});

it('should delete feedback', function () {
    $feedback = Feedback::factory()->create();

    livewire(FeedbackResource\Pages\EditFeedback::class, [
        'record' => $feedback->getKey(),
    ])
        ->callPageAction(DeleteAction::class);

    assertSoftDeleted($feedback);
});
