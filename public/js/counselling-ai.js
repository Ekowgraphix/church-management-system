/**
 * Counselling AI Features
 * AI-powered note summarization, follow-up management, and insights
 */

const CounsellingAI = {
    /**
     * Summarize session notes using AI
     */
    async summarizeNotes(sessionId, notes) {
        try {
            // Show loading state
            this.showLoading('Analyzing notes with AI...');

            const response = await fetch(`/counselling/${sessionId}/summarize`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ notes })
            });

            const data = await response.json();

            if (data.success) {
                this.displaySummary(data);
                this.hideLoading();
                return data;
            }
        } catch (error) {
            console.error('Error summarizing notes:', error);
            this.showError('Failed to generate AI summary');
        }
    },

    /**
     * Display AI summary in modal
     */
    displaySummary(data) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">🤖 AI Session Summary</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Summary -->
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                            </svg>
                            Summary
                        </h4>
                        <p class="text-gray-700">${data.summary}</p>
                    </div>

                    <!-- Key Points -->
                    <div class="bg-green-50 rounded-xl p-6">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Key Points
                        </h4>
                        <ul class="space-y-2">
                            ${data.key_points.map(point => `
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">•</span>
                                    <span class="text-gray-700">${point}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>

                    <!-- Action Items -->
                    <div class="bg-purple-50 rounded-xl p-6">
                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            Action Items
                        </h4>
                        <ul class="space-y-2">
                            ${data.action_items.map(item => `
                                <li class="flex items-start">
                                    <input type="checkbox" class="mt-1 mr-2 rounded text-purple-600">
                                    <span class="text-gray-700">${item}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>

                    <div class="flex space-x-3">
                        <button onclick="CounsellingAI.copySummary(${JSON.stringify(data).replace(/"/g, '&quot;')})" 
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition">
                            Copy Summary
                        </button>
                        <button onclick="this.closest('.fixed').remove()" 
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    },

    /**
     * Copy summary to clipboard
     */
    copySummary(data) {
        const text = `
AI SESSION SUMMARY
==================

${data.summary}

KEY POINTS:
${data.key_points.map((p, i) => `${i + 1}. ${p}`).join('\n')}

ACTION ITEMS:
${data.action_items.map((a, i) => `${i + 1}. ${a}`).join('\n')}
        `.trim();

        navigator.clipboard.writeText(text).then(() => {
            this.showSuccess('Summary copied to clipboard!');
        });
    },

    /**
     * Create follow-up reminder
     */
    async createFollowup(sessionId, data) {
        try {
            const response = await fetch(`/counselling/${sessionId}/followup`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                return result.followup;
            }
        } catch (error) {
            console.error('Error creating follow-up:', error);
            this.showError('Failed to create follow-up');
        }
    },

    /**
     * Show follow-up form modal
     */
    showFollowupForm(sessionId) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-2xl max-w-md w-full p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">📅 Schedule Follow-up</h3>
                
                <form onsubmit="CounsellingAI.handleFollowupSubmit(event, ${sessionId})" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Date</label>
                        <input type="date" name="follow_up_date" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                        <select name="priority" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Add any notes..."></textarea>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition">
                            Create Follow-up
                        </button>
                        <button type="button" onclick="this.closest('.fixed').remove()"
                                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(modal);
    },

    /**
     * Handle follow-up form submission
     */
    async handleFollowupSubmit(event, sessionId) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);

        await this.createFollowup(sessionId, data);
        event.target.closest('.fixed').remove();
        setTimeout(() => window.location.reload(), 1000);
    },

    /**
     * Export session as encrypted PDF
     */
    async exportPDF(sessionId) {
        this.showLoading('Generating encrypted PDF...');
        
        try {
            const response = await fetch(`/counselling/${sessionId}/export-pdf`);
            const data = await response.json();
            
            this.hideLoading();
            this.showSuccess(data.message);
        } catch (error) {
            this.hideLoading();
            this.showError('Failed to export PDF');
        }
    },

    /**
     * UI Helper: Show loading
     */
    showLoading(message) {
        const loader = document.createElement('div');
        loader.id = 'ai-loader';
        loader.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
        loader.innerHTML = `
            <div class="bg-white rounded-2xl p-8 text-center">
                <div class="animate-spin w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
                <p class="text-gray-900 font-semibold">${message}</p>
            </div>
        `;
        document.body.appendChild(loader);
    },

    /**
     * UI Helper: Hide loading
     */
    hideLoading() {
        const loader = document.getElementById('ai-loader');
        if (loader) loader.remove();
    },

    /**
     * UI Helper: Show success
     */
    showSuccess(message) {
        this.showToast(message, 'success');
    },

    /**
     * UI Helper: Show error
     */
    showError(message) {
        this.showToast(message, 'error');
    },

    /**
     * UI Helper: Show toast notification
     */
    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-4 rounded-xl shadow-lg text-white font-semibold z-50 animate-slide-up ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        }`;
        toast.innerHTML = `
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    ${type === 'success' 
                        ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                        : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                    }
                </svg>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
};

// Global functions for inline onclick handlers
window.CounsellingAI = CounsellingAI;
