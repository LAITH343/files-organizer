document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const fileInput = document.getElementById('textFile');
    const uploadBtn = document.getElementById('uploadBtn');
    const loading = document.getElementById('loading');
    const errorContainer = document.getElementById('errorContainer');
    const resultsContainer = document.getElementById('resultsContainer');

    errorContainer.innerHTML = '';
    resultsContainer.innerHTML = '';

    if (!fileInput.files[0]) {
        showError('Please select a text file.');
        return;
    }

    uploadBtn.disabled = true;
    loading.classList.add('show');

    try {
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        const response = await fetch('/api/v1/organize-file', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();

        if (response.status === 200) {
            displayResults(data.data);
        } else {
            showError(data.message || 'An error occurred while processing the file.');
        }

    } catch (error) {
        showError('Network error: Unable to connect to the server.');
    } finally {
        uploadBtn.disabled = false;
        loading.classList.remove('show');
    }
});

function showError(message) {
    const errorContainer = document.getElementById('errorContainer');
    errorContainer.innerHTML = `<div class="error">${message}</div>`;
}

function displayResults(data) {
    const resultsContainer = document.getElementById('resultsContainer');

    if (!data || Object.keys(data).length === 0) {
        resultsContainer.innerHTML = '<div class="results"><h2>No organized files found</h2></div>';
        return;
    }

    let html = '<div class="results"><h2>Organized Files</h2>';

    Object.entries(data).forEach(([personName, files]) => {
        html += `
            <div class="person-group">
                <div class="person-name">${personName} (${files.length} files)</div>
        `;

        files.forEach(file => {
            html += `<div class="file-item">${file}</div>`;
        });

        html += `</div>`;
    });

    html += '</div>';
    resultsContainer.innerHTML = html;
}