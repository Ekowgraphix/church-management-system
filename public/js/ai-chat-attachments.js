/**
 * AI Chat Attachment Handling
 * File upload and processing for AI Chat
 */

let currentAttachment = null;
const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
const ALLOWED_TYPES = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'text/plain',
    'text/csv'
];

// Handle file upload
function handleFileUpload(event) {
    const file = event.target.files[0];
    
    if (!file) return;

    // Validate file size
    if (file.size > MAX_FILE_SIZE) {
        alert(`❌ File too large!\n\nMaximum file size is 5MB.\nYour file: ${(file.size / 1024 / 1024).toFixed(2)}MB`);
        event.target.value = '';
        return;
    }

    // Validate file type
    if (!ALLOWED_TYPES.includes(file.type)) {
        alert(`❌ File type not supported!\n\nAllowed types:\n• PDF (.pdf)\n• Word (.doc, .docx)\n• Text (.txt)\n• CSV (.csv)`);
        event.target.value = '';
        return;
    }

    // Store attachment
    currentAttachment = file;
    showAttachmentPreview(file);
    
    console.log('File attached:', file.name);
}

// Show attachment preview
function showAttachmentPreview(file) {
    const preview = document.getElementById('attachmentPreview');
    
    if (!preview) {
        // Create preview element
        const container = document.querySelector('.glass-card.p-4.border-t');
        const div = document.createElement('div');
        div.id = 'attachmentPreview';
        div.className = 'mb-3 glass-card p-3 rounded-lg';
        div.innerHTML = getPreviewHTML(file);
        container.insertBefore(div, container.firstChild);
    } else {
        preview.innerHTML = getPreviewHTML(file);
        preview.classList.remove('hidden');
    }
}

// Get preview HTML
function getPreviewHTML(file) {
    const icon = getFileIcon(file.type);
    const size = formatFileSize(file.size);
    
    return `
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-${icon} text-cyan-400 text-xl"></i>
                <div>
                    <p class="text-white font-semibold text-sm" id="fileName">${file.name}</p>
                    <p class="text-white/60 text-xs" id="fileSize">${size}</p>
                </div>
            </div>
            <button onclick="removeAttachment()" class="text-red-400 hover:text-red-300 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
}

// Remove attachment
function removeAttachment() {
    currentAttachment = null;
    const preview = document.getElementById('attachmentPreview');
    if (preview) {
        preview.classList.add('hidden');
    }
    const fileInput = document.getElementById('fileUpload');
    if (fileInput) {
        fileInput.value = '';
    }
    console.log('Attachment removed');
}

// Get file icon based on type
function getFileIcon(type) {
    if (type === 'application/pdf') return 'file-pdf';
    if (type.includes('word')) return 'file-word';
    if (type === 'text/plain') return 'file-alt';
    if (type === 'text/csv') return 'file-csv';
    return 'file';
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

// Process attachment with AI
async function processAttachmentWithAI(file, userQuestion) {
    // Show processing message
    const processingMsg = `Processing "${file.name}"... This may take a moment.`;
    showNotification(processingMsg);

    try {
        // Read file content
        const content = await readFileContent(file);
        
        // Generate AI response based on file type and content
        const response = generateFileAnalysis(file.name, file.type, content, userQuestion);
        
        return response;
    } catch (error) {
        console.error('Error processing file:', error);
        return `❌ Error processing file: ${error.message}\n\nPlease try again or use a different file.`;
    }
}

// Read file content
function readFileContent(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            resolve(e.target.result);
        };
        
        reader.onerror = function(e) {
            reject(new Error('Failed to read file'));
        };
        
        if (file.type === 'application/pdf') {
            // For PDF, we'd need a library like PDF.js
            // For now, return placeholder
            resolve('[PDF content - actual extraction requires PDF.js library]');
        } else {
            reader.readAsText(file);
        }
    });
}

// Generate file analysis
function generateFileAnalysis(fileName, fileType, content, userQuestion) {
    const analysis = `📄 **File Analysis: ${fileName}**\n\n`;
    
    // Extract key information
    const wordCount = content.split(/\s+/).length;
    const charCount = content.length;
    const lines = content.split('\n').length;
    
    let response = analysis;
    response += `**Document Statistics:**\n`;
    response += `• Words: ${wordCount.toLocaleString()}\n`;
    response += `• Characters: ${charCount.toLocaleString()}\n`;
    response += `• Lines: ${lines.toLocaleString()}\n\n`;
    
    // AI analysis based on question
    if (userQuestion.toLowerCase().includes('summarize')) {
        response += `**Summary:**\n`;
        response += `This document appears to contain ${wordCount > 500 ? 'detailed' : 'concise'} information. `;
        response += `The content covers various topics and would benefit from further review.\n\n`;
        response += `**Key Points:**\n`;
        response += `• Main theme identified\n`;
        response += `• Multiple sections present\n`;
        response += `• ${lines > 100 ? 'Comprehensive' : 'Brief'} coverage\n\n`;
    } else if (userQuestion.toLowerCase().includes('analyze')) {
        response += `**Analysis:**\n`;
        response += `• Document structure is ${lines > 50 ? 'well-organized' : 'concise'}\n`;
        response += `• Content density: ${wordCount > 1000 ? 'High' : 'Moderate'}\n`;
        response += `• Readability: ${wordCount / lines < 15 ? 'Easy' : 'Detailed'}\n\n`;
    } else {
        response += `**Overview:**\n`;
        response += `I've processed your document. You can ask me to:\n`;
        response += `• Summarize the content\n`;
        response += `• Analyze key themes\n`;
        response += `• Extract specific information\n`;
        response += `• Generate questions from the text\n\n`;
    }
    
    response += `*Note: Full AI document processing requires OpenAI API integration.*`;
    
    return response;
}

// Export functions
window.handleFileUpload = handleFileUpload;
window.removeAttachment = removeAttachment;
window.processAttachmentWithAI = processAttachmentWithAI;
window.currentAttachment = currentAttachment;

console.log('✅ AI Chat Attachments module loaded');
