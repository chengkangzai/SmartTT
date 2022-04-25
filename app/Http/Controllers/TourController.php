<?php

namespace App\Http\Controllers;

use App\Actions\Tour\DestroyTourAction;
use App\Actions\Tour\StoreTourAction;
use App\Actions\Tour\UpdateTourAction;
use App\Models\Country;
use App\Models\Tour;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TourController extends Controller
{
    public function index(): Factory|View|Application
    {
        $tours = Tour::with('countries')->orderByDesc('id')->paginate(10);

        return view('smartTT.tour.index', compact('tours'));
    }

    public function create(): Factory|View|Application
    {
        $countries = Country::pluck('name', 'id');

        return view('smartTT.tour.create', compact('countries'));
    }

    public function store(Request $request, StoreTourAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all());
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('tours.index')->with('success', __('Tour created successfully'));
    }

    public function show(Tour $tour): Factory|View|Application
    {
        $itineraryUrl = Storage::url($tour->itinerary_url);
        $thumbnailUrl = Storage::url($tour->thumbnail_url);
        $tourDes = $tour->description()->paginate(9);

        return view('smartTT.tour.show', compact('tour', 'itineraryUrl', 'thumbnailUrl', 'tourDes'));
    }

    public function edit(Tour $tour): Factory|View|Application
    {
        $countries = Country::pluck('name', 'id');

        return view('smartTT.tour.edit', compact('tour', 'countries'));
    }

    public function update(Request $request, Tour $tour, UpdateTourAction $action): RedirectResponse
    {
        try {
            $action->execute($request->all(), $tour);
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->validator->errors())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('tours.index')->with('success', __('Tour updated successfully'));
    }

    public function destroy(Tour $tour, DestroyTourAction $action): RedirectResponse
    {
        try {
            $action->execute($tour);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

        return redirect()->route('tours.index')->with('success', __('Tour deleted successfully'));
    }
}
