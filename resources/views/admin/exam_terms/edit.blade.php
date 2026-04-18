@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h3 class="text-gray-700 text-3xl font-medium">Edit Exam Term</h3>

    <div class="mt-8">
        <div class="w-full max-w-lg mx-auto bg-white shadow rounded-lg p-6">
            <h4 class="text-xl font-semibold mb-4 text-gray-800">Edit Term: {{ $examTerm->name }}</h4>
            <form action="{{ route('admin.exam-terms.update', $examTerm->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Term Name</label>
                    <div class="relative">
                        <select name="name" class="block appearance-none w-full bg-white border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-200' }} text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            <option value="">-- Select Term --</option>
                            @foreach(['1st Term', '2nd Term', 'Mid Term', '3rd Term', 'Final Term'] as $option)
                            <option value="{{ $option }}" {{ old('name', $examTerm->name) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                    <input type="date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('start_date') border-red-500 @enderror" value="{{ old('start_date', $examTerm->start_date->format('Y-m-d')) }}">
                    @error('start_date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                    <input type="date" name="end_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('end_date') border-red-500 @enderror" value="{{ old('end_date', $examTerm->end_date->format('Y-m-d')) }}">
                    @error('end_date') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Applicable Classes</label>
                    <div class="max-h-48 overflow-y-auto border rounded p-2 bg-gray-50">
                        @foreach($classes as $class)
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="class_ids[]" value="{{ $class->id }}" id="class_{{ $class->id }}"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                {{ in_array($class->id, old('class_ids', $examTerm->classes->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label for="class_{{ $class->id }}" class="ml-2 block text-sm text-gray-900">
                                {{ $class->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('class_ids') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Exam Rules (Global Instructions)</label>
                    <textarea name="rules" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('rules', $examTerm->rules) }}</textarea>
                    @error('rules') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('admin.exam-terms.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Update Term
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection