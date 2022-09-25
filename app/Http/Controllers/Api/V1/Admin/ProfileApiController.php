<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\Admin\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('profile_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProfileResource(Profile::with(['user'])->advancedFilter());
    }

    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create($request->validated());

        return (new ProfileResource($profile))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function create()
    {
        abort_if(Gate::denies('profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'meta' => [
                'user'      => User::get(['id', 'email']),
                'sex'       => Profile::SEX_SELECT,
                'religion'  => Profile::RELIGION_SELECT,
                'ethnicity' => Profile::ETHNICITY_SELECT,
            ],
        ]);
    }

    public function show(Profile $profile)
    {
        abort_if(Gate::denies('profile_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ProfileResource($profile->load(['user']));
    }

    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        $profile->update($request->validated());

        return (new ProfileResource($profile))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function edit(Profile $profile)
    {
        abort_if(Gate::denies('profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response([
            'data' => new ProfileResource($profile->load(['user'])),
            'meta' => [
                'user'      => User::get(['id', 'email']),
                'sex'       => Profile::SEX_SELECT,
                'religion'  => Profile::RELIGION_SELECT,
                'ethnicity' => Profile::ETHNICITY_SELECT,
            ],
        ]);
    }

    public function destroy(Profile $profile)
    {
        abort_if(Gate::denies('profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $profile->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
