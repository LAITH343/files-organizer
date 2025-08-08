@extends('layouts.home.app')

@section('title', 'File Organizer')

@section('content')
    <div class="container">
        <h1>File Organizer</h1>

        <form id="uploadForm" enctype="multipart/form-data">
            <div class="upload-section">
                <input type="file" id="textFile" name="file" accept=".txt" class="file-input" required>

                <button type="submit" class="upload-btn" id="uploadBtn">
                    Upload File
                </button>
            </div>
        </form>

        <div class="loading" id="loading">
            <p>Processing...</p>
        </div>

        <div id="errorContainer"></div>
        <div id="resultsContainer"></div>
    </div>
@endsection
