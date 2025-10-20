/**
 * AI Chat Voice Features
 * Voice input/output functionality for AI Chat
 */

// Initialize speech recognition
let recognition = null;
let synthesis = window.speechSynthesis;
let isVoiceMode = false;
let isRecording = false;

// Check for browser support
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    recognition = new SpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = true;
    recognition.lang = 'en-US';

    recognition.onstart = function() {
        isRecording = true;
        showVoiceRecordingUI();
        console.log('Voice recognition started');
    };

    recognition.onresult = function(event) {
        let interimTranscript = '';
        let finalTranscript = '';

        for (let i = event.resultIndex; i < event.results.length; i++) {
            const transcript = event.results[i][0].transcript;
            if (event.results[i].isFinal) {
                finalTranscript += transcript;
            } else {
                interimTranscript += transcript;
            }
        }

        // Update input with transcript
        if (finalTranscript) {
            document.getElementById('messageInput').value = finalTranscript;
        }
    };

    recognition.onerror = function(event) {
        console.error('Speech recognition error:', event.error);
        isRecording = false;
        hideVoiceRecordingUI();
        
        if (event.error === 'not-allowed') {
            alert('Microphone access denied. Please enable microphone permissions.');
        } else if (event.error === 'no-speech') {
            alert('No speech detected. Please try again.');
        } else {
            alert('Voice recognition error: ' + event.error);
        }
    };

    recognition.onend = function() {
        isRecording = false;
        hideVoiceRecordingUI();
        console.log('Voice recognition ended');
    };
}

// Start voice input
function startVoiceInput() {
    if (!recognition) {
        alert('❌ Voice recognition not supported in this browser.\n\nPlease use Chrome, Edge, or Safari for voice features.');
        return;
    }

    if (isRecording) {
        stopVoiceInput();
        return;
    }

    try {
        recognition.start();
    } catch (error) {
        console.error('Failed to start recognition:', error);
        alert('Could not start voice recognition. Please try again.');
    }
}

// Stop voice input
function stopVoiceInput() {
    if (recognition && isRecording) {
        recognition.stop();
    }
}

// Text-to-speech
function speakText(text, rate = 1.0, pitch = 1.0) {
    if (!synthesis) {
        console.warn('Text-to-speech not supported');
        return;
    }

    // Cancel any ongoing speech
    synthesis.cancel();

    // Clean text for speech
    const cleanText = text
        .replace(/\*\*/g, '')
        .replace(/\*/g, '')
        .replace(/\n\n/g, '. ')
        .replace(/\n/g, ' ')
        .replace(/[📖🙏📱💰✅]/g, '');

    const utterance = new SpeechSynthesisUtterance(cleanText);
    utterance.rate = rate;
    utterance.pitch = pitch;
    utterance.volume = 1.0;
    utterance.lang = 'en-US';

    // Visual feedback
    utterance.onstart = function() {
        console.log('Speech started');
        showSpeakingIndicator();
    };

    utterance.onend = function() {
        console.log('Speech ended');
        hideSpeakingIndicator();
    };

    utterance.onerror = function(event) {
        console.error('Speech error:', event);
        hideSpeakingIndicator();
    };

    synthesis.speak(utterance);
}

// Stop speech
function stopSpeaking() {
    if (synthesis) {
        synthesis.cancel();
        hideSpeakingIndicator();
    }
}

// Toggle voice mode (auto-play responses)
function toggleVoiceMode() {
    isVoiceMode = !isVoiceMode;
    const btn = document.getElementById('voiceModeBtn');
    
    if (isVoiceMode) {
        btn.classList.add('bg-cyan-500/30');
        btn.title = 'Voice Mode: ON';
        showNotification('🎤 Voice mode enabled - AI will speak responses');
    } else {
        btn.classList.remove('bg-cyan-500/30');
        btn.title = 'Voice Mode: OFF';
        showNotification('🔇 Voice mode disabled');
        stopSpeaking();
    }
}

// UI helpers
function showVoiceRecordingUI() {
    // Update button
    const btn = document.getElementById('voiceInputBtn');
    if (btn) {
        btn.classList.add('bg-red-500/30', 'animate-pulse');
        btn.innerHTML = '<i class="fas fa-stop"></i>';
    }

    // Show recording indicator
    const indicator = document.getElementById('voiceRecordingIndicator');
    if (indicator) {
        indicator.classList.remove('hidden');
    } else {
        // Create indicator if it doesn't exist
        const container = document.querySelector('.glass-card.p-4.border-t');
        const div = document.createElement('div');
        div.id = 'voiceRecordingIndicator';
        div.className = 'mb-3 glass-card p-4 rounded-lg voice-recording';
        div.innerHTML = `
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center animate-pulse">
                    <i class="fas fa-microphone text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-white font-bold">Recording...</p>
                    <p class="text-white/60 text-sm">Speak clearly and naturally</p>
                </div>
                <button onclick="stopVoiceInput()" class="glass-card px-4 py-2 rounded-lg text-white hover:bg-white/10 transition">
                    <i class="fas fa-stop mr-2"></i>Stop
                </button>
            </div>
        `;
        container.insertBefore(div, container.firstChild);
    }
}

function hideVoiceRecordingUI() {
    // Reset button
    const btn = document.getElementById('voiceInputBtn');
    if (btn) {
        btn.classList.remove('bg-red-500/30', 'animate-pulse');
        btn.innerHTML = '<i class="fas fa-microphone"></i>';
    }

    // Hide indicator
    const indicator = document.getElementById('voiceRecordingIndicator');
    if (indicator) {
        indicator.classList.add('hidden');
    }
}

function showSpeakingIndicator() {
    const statusText = document.getElementById('statusText');
    if (statusText) {
        statusText.textContent = '🔊 Speaking...';
    }
}

function hideSpeakingIndicator() {
    const statusText = document.getElementById('statusText');
    if (statusText) {
        statusText.textContent = 'Online & Ready';
    }
}

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-20 right-4 glass-card p-4 rounded-xl text-white z-50 animate-slideIn';
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-info-circle text-cyan-400"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Auto-speak AI responses when voice mode is on
function autoSpeakIfEnabled(text) {
    if (isVoiceMode && text) {
        speakText(text);
    }
}

// Export functions
window.startVoiceInput = startVoiceInput;
window.stopVoiceInput = stopVoiceInput;
window.speakText = speakText;
window.stopSpeaking = stopSpeaking;
window.toggleVoiceMode = toggleVoiceMode;
window.autoSpeakIfEnabled = autoSpeakIfEnabled;

console.log('✅ AI Chat Voice module loaded');
