@extends('backend.master')

@section('title', 'FAQ Edit')

@section('content')
    <main class="app-content content">
        <div class="">
            <div class="col-lg-12">
                <a href="{{ route('faq.index') }}" class="btn btn-sm btn-light me-3 shadow-sm">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a><br>
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-header text-white rounded-top-4 d-flex align-items-center"
                        {{-- style="background: linear-gradient(135deg, #39b6e7, #42b7e6);" --}}
                        >
                        <h4 class="mb-0 text-start flex-grow-1">Edit FAQ</h4>
                    </div>

                    <div class="card-body p-4">
                        <form id="editfaq" action="{{ route('faq.update', $faqs->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 row">
                                <label class="col-2 col-form-label text-center fw-bold">Question ?</label>
                                <div class="col-10">
                                    <textarea name="que" class="form-control rounded-3 shadow-sm" placeholder="Type your question..." rows="2">{{ old('que', $faqs->que ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-2 col-form-label text-center fw-bold">Answer</label>
                                <div class="col-10">
                                    <textarea id="ans" name="ans"
                                        class="ck-editor form-control rounded-3 shadow-sm @error('ans') is-invalid @enderror">{{ old('ans', $faqs->ans ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary btn-sm px-4 py-2 shadow-sm">
                                   <i class="bi bi-save me-1"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
