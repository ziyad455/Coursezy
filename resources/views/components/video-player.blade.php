@props(['lesson', 'course', 'nextLesson' => null])

<div class="video-player-root group/player relative w-full h-full rounded-2xl overflow-hidden bg-black shadow-2xl transition-all duration-500 hover:shadow-indigo-500/10" 
     x-data="premiumVideoPlayer()"
     @keydown.window="handleGlobalKeydown($event)"
     @mousemove="showControlsAndHideLater()"
     @touchstart="showControlsAndHideLater()">
    
    <!-- Video Element -->
    <video 
        id="main-video-player" 
        class="w-full h-full object-contain cursor-pointer"
        @click="togglePlay"
        @dblclick="toggleFullscreen"
        @loadedmetadata="onVideoLoaded"
        @timeupdate="onTimeUpdate"
        @ended="onVideoEnded"
        @waiting="isLoading = true"
        @playing="isLoading = false"
        @progress="updateBuffered"
        preload="auto">
        @if($lesson->video_url)
            <source src="{{ $lesson->video_url }}" type="video/mp4">
        @endif
    </video>

    <!-- Visual Feedback Overlays (Center) -->
    <div class="absolute inset-0 pointer-events-none flex items-center justify-center z-50">
        <!-- Play/Pause Feedback -->
        <div x-show="feedback.play" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-50"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-150"
             class="p-8 rounded-full bg-white/10 backdrop-blur-2xl border border-white/20 shadow-2xl">
            <template x-if="isPlaying">
                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </template>
            <template x-if="!isPlaying">
                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
            </template>
        </div>

        <!-- Volume/Seek Feedback -->
        <div x-show="feedback.active" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="flex flex-col items-center space-y-3 px-6 py-4 rounded-2xl bg-black/40 backdrop-blur-2xl border border-white/10 shadow-2xl">
            <span class="text-white text-2xl font-bold tracking-tight" x-text="feedback.text"></span>
            <div class="w-32 h-1.5 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 transition-all duration-150" :style="`width: ${feedback.percent}%`"></div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[2px] z-40">
        <div class="relative w-20 h-20">
            <div class="absolute inset-0 border-4 border-indigo-500/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-t-indigo-500 rounded-full animate-spin"></div>
        </div>
    </div>

    <!-- Premium Controls Container -->
    <div 
        class="absolute inset-0 flex flex-col justify-end transition-all duration-700 z-30 pointer-events-none"
        :class="showControls ? 'opacity-100 pointer-events-auto bg-gradient-to-t from-black/80 via-transparent to-black/20' : 'opacity-0 pointer-events-none'">
        
        <!-- Top Toolbar (Back/Title) -->
        <div class="absolute top-0 left-0 right-0 p-8 flex items-center justify-between transform transition-transform duration-500"
             :class="showControls ? 'translate-y-0' : '-translate-y-full'">
            <div class="flex items-center space-x-4">
                <button @click="window.history.back()" class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 transition-all hover:scale-105 active:scale-95 group">
                    <svg class="w-6 h-6 text-white group-hover:text-indigo-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-indigo-400 uppercase tracking-widest">{{ $course->title }}</span>
                    <h2 class="text-xl font-semibold text-white tracking-tight">{{ $lesson->title }}</h2>
                </div>
            </div>
        </div>

        <!-- Progress Scrubber Area -->
        <div class="px-8 pb-4 group/scrubber select-none cursor-pointer" 
             @mousemove="updateScrub($event)"
             @mousedown="startScrub($event)"
             @click="seekToPosition($event)">
            
            <div class="relative h-1.5 w-full bg-white/10 rounded-full transition-all duration-300 group-hover/scrubber:h-2.5">
                <!-- Buffered Progress -->
                <div class="absolute inset-y-0 left-0 bg-white/10 rounded-full transition-all duration-500" :style="`width: ${bufferedPercent}%`"></div>
                
                <!-- Active Progress -->
                <div class="absolute inset-y-0 left-0 bg-gradient-to-r from-indigo-500 via-indigo-400 to-purple-500 rounded-full transition-all duration-75 shadow-[0_0_15px_-3px_rgba(99,102,241,0.5)]" :style="`width: ${progressPercent}%` text-indigo-400">
                    <!-- Premium Knob -->
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-4 h-4 bg-white rounded-full shadow-2xl scale-0 group-hover/scrubber:scale-100 transition-transform duration-200 border-2 border-indigo-600"></div>
                </div>

                <!-- Hover Preview Tooltip -->
                <div x-show="hover.active" 
                     class="absolute bottom-6 px-3 py-2 bg-gray-900/95 backdrop-blur-2xl border border-white/10 rounded-xl shadow-2xl pointer-events-none -translate-x-1/2 whitespace-nowrap"
                     :style="`left: ${hover.percent}%`">
                    <span class="text-sm font-bold text-white tracking-tighter" x-text="hover.time"></span>
                </div>
            </div>
        </div>

        <!-- Main Controls Bar -->
        <div class="px-8 pb-8 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <!-- Play/Pause Toggle -->
                <button @click="togglePlay" class="text-white hover:text-indigo-400 transform transition-all hover:scale-110 active:scale-90">
                    <template x-if="!isPlaying">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </template>
                    <template x-if="isPlaying">
                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/></svg>
                    </template>
                </button>

                <!-- Skip Back/Forward -->
                <div class="flex items-center space-x-4">
                    <button @click="rewind()" class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/5">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11 18V6l-8.5 6 8.5 6zm.5-6l8.5 6V6l-8.5 6z"/></svg>
                    </button>
                    <button @click="forward()" class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/5">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M13 6v12l8.5-6L13 6zM4 18l8.5-6L4 6v12z"/></svg>
                    </button>
                </div>

                <!-- Volume Engine -->
                <div class="flex items-center group/volume ml-2">
                    <button @click="toggleMute" class="text-white/80 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/5 mr-2">
                        <template x-if="isMuted || volume === 0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></template>
                        <template x-if="!isMuted && volume > 0 && volume < 0.5">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 9v6h4l5 5V4l-5 5H7z"/></svg></template>
                        <template x-if="!isMuted && volume >= 0.5">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/></template>
                    </button>
                    <div class="relative w-0 group-hover/volume:w-24 transition-all duration-300 h-1 bg-white/20 rounded-full overflow-hidden">
                        <input type="range" x-model="volume" min="0" max="1" step="0.01" @input="updateVolume" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="h-full bg-indigo-400" :style="`width: ${volume * 100}%`"></div>
                    </div>
                </div>

                <!-- Time Engine -->
                <div class="flex items-center space-x-2 px-3 py-1.5 bg-white/5 rounded-xl border border-white/5 backdrop-blur-md">
                    <span class="text-sm font-bold text-white tracking-widest" x-text="formatTime(currentTime)"></span>
                    <span class="text-white/40 text-xs">/</span>
                    <span class="text-sm font-bold text-white/60 tracking-widest" x-text="formatTime(duration)"></span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Next Lesson CTA -->
                @if($nextLesson)
                    <a href="{{ route('student.learn.lesson', ['course' => $course->id, 'lesson' => $nextLesson->id]) }}" 
                       class="flex items-center space-x-3 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-lg hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 active:translate-y-0 group/next">
                        <span class="text-sm font-bold text-white uppercase tracking-wider">Next Lesson</span>
                        <svg class="w-4 h-4 text-white group-hover/next:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                @endif

                <!-- Settings & Fullscreen -->
                <div class="flex items-center p-1.5 bg-white/5 rounded-2xl backdrop-blur-xl border border-white/5">
                    <!-- Speed Engine -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="px-4 py-2 text-sm font-black text-white rounded-xl hover:bg-white/10 transition-colors">
                            <span x-text="`${playbackRate}x`"></span>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute bottom-full mb-4 right-0 min-w-[140px] bg-gray-900/95 backdrop-blur-2xl rounded-2xl border border-white/10 shadow-2xl p-2 overflow-hidden z-50">
                            <template x-for="rate in playbackRates" :key="rate">
                                <button @click="setRate(rate); open = false" 
                                        class="flex items-center justify-between w-full px-4 py-2.5 rounded-xl text-sm transition-all"
                                        :class="playbackRate === rate ? 'bg-indigo-600 text-white font-bold' : 'text-white/60 hover:text-white hover:bg-white/5'">
                                    <span x-text="`${rate}x`"></span>
                                    <template x-if="playbackRate === rate">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="w-px h-6 bg-white/10 mx-2"></div>

                    <!-- PiP -->
                    <button @click="togglePiP" class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 11h-8v6h8v-6zm4 8V4.98C23 3.88 22.1 3 21 3H3c-1.1 0-2 .88-2 1.98V19c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2zm-2 .02H3V4.97h18v14.05z"/></svg>
                    </button>

                    <!-- Fullscreen -->
                    <button @click="toggleFullscreen" class="p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <template x-if="!isFullscreen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        </template>
                        <template x-if="isFullscreen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </template>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function premiumVideoPlayer() {
    return {
        video: null,
        isPlaying: false,
        isLoading: true,
        isMuted: false,
        isFullscreen: false,
        showControls: true,
        controlsTimeout: null,
        
        currentTime: 0,
        duration: 0,
        progressPercent: 0,
        bufferedPercent: 0,
        
        volume: 1,
        playbackRate: 1,
        playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2],
        
        hover: { active: false, time: '0:00', percent: 0 },
        feedback: { play: false, active: false, text: '', percent: 0, timeout: null },

        init() {
            this.video = this.$el.querySelector('video');
            this.volume = localStorage.getItem('player-volume') || 1;
            this.video.volume = this.volume;
            
            this.setupKeyboardShortcuts();
            this.showControlsAndHideLater();
            
            // Auto-save progress
            setInterval(() => this.saveProgress(), 5000);
            this.loadProgress();
        },

        togglePlay() {
            if (this.video.paused) {
                this.video.play();
                this.isPlaying = true;
            } else {
                this.video.pause();
                this.isPlaying = false;
            }
            this.triggerFeedback('play');
        },

        triggerFeedback(type, text = '', percent = 0) {
            clearTimeout(this.feedback.timeout);
            
            if (type === 'play') {
                this.feedback.play = true;
                setTimeout(() => this.feedback.play = false, 600);
            } else {
                this.feedback.active = true;
                this.feedback.text = text;
                this.feedback.percent = percent;
                this.feedback.timeout = setTimeout(() => this.feedback.active = false, 1000);
            }
        },

        onVideoLoaded() {
            this.duration = this.video.duration;
            this.isLoading = false;
        },

        onTimeUpdate() {
            this.currentTime = this.video.currentTime;
            this.progressPercent = (this.currentTime / this.duration) * 100;
        },

        updateBuffered() {
            if (this.video.buffered.length > 0) {
                this.bufferedPercent = (this.video.buffered.end(this.video.buffered.length - 1) / this.video.duration) * 100;
            }
        },

        onVideoEnded() {
            this.isPlaying = false;
            this.showControls = true;
        },

        formatTime(seconds) {
            if (!seconds) return '0:00';
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            if (h > 0) return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            return `${m}:${s.toString().padStart(2, '0')}`;
        },

        showControlsAndHideLater() {
            this.showControls = true;
            clearTimeout(this.controlsTimeout);
            if (this.isPlaying) {
                this.controlsTimeout = setTimeout(() => {
                    this.showControls = false;
                }, 3000);
            }
        },

        updateVolume() {
            this.video.volume = this.volume;
            this.isMuted = this.volume == 0;
            localStorage.setItem('player-volume', this.volume);
            this.triggerFeedback('volume', `${Math.round(this.volume * 100)}%`, this.volume * 100);
        },

        toggleMute() {
            this.isMuted = !this.isMuted;
            this.video.muted = this.isMuted;
            this.triggerFeedback('volume', this.isMuted ? 'Muted' : `${Math.round(this.volume * 100)}%`, this.isMuted ? 0 : this.volume * 100);
        },

        setRate(rate) {
            this.playbackRate = rate;
            this.video.playbackRate = rate;
            this.triggerFeedback('rate', `${rate}x`, (rate / 2) * 100);
        },

        rewind() {
            this.video.currentTime -= 10;
            this.triggerFeedback('seek', '-10s', (this.video.currentTime / this.duration) * 100);
        },

        forward() {
            this.video.currentTime += 10;
            this.triggerFeedback('seek', '+10s', (this.video.currentTime / this.duration) * 100);
        },

        updateScrub(e) {
            this.hover.active = true;
            const rect = e.currentTarget.getBoundingClientRect();
            const pos = (e.clientX - rect.left) / rect.width;
            this.hover.percent = Math.max(0, Math.min(100, pos * 100));
            this.hover.time = this.formatTime(pos * this.duration);
        },

        seekToPosition(e) {
            const rect = e.currentTarget.getBoundingClientRect();
            const pos = (e.clientX - rect.left) / rect.width;
            this.video.currentTime = pos * this.duration;
        },

        toggleFullscreen() {
            if (!document.fullscreenElement) {
                this.$el.requestFullscreen();
                this.isFullscreen = true;
            } else {
                document.exitFullscreen();
                this.isFullscreen = false;
            }
        },

        togglePiP() {
            if (document.pictureInPictureElement) {
                document.exitPictureInPicture();
            } else {
                this.video.requestPictureInPicture();
            }
        },

        handleGlobalKeydown(e) {
            if (['INPUT', 'TEXTAREA'].includes(e.target.tagName)) return;
            
            const keys = {
                ' ': () => this.togglePlay(),
                'k': () => this.togglePlay(),
                'f': () => this.toggleFullscreen(),
                'm': () => this.toggleMute(),
                'ArrowLeft': () => this.rewind(),
                'ArrowRight': () => this.forward(),
                'ArrowUp': () => {
                    this.volume = Math.min(1, parseFloat(this.volume) + 0.05);
                    this.updateVolume();
                },
                'ArrowDown': () => {
                    this.volume = Math.max(0, parseFloat(this.volume) - 0.05);
                    this.updateVolume();
                }
            };
            
            if (keys[e.key]) {
                e.preventDefault();
                keys[e.key]();
                this.showControlsAndHideLater();
            }
        },

        saveProgress() {
            if (this.duration > 0) {
                localStorage.setItem(`lesson_progress_{{ $lesson->id }}`, this.currentTime);
            }
        },

        loadProgress() {
            const saved = localStorage.getItem(`lesson_progress_{{ $lesson->id }}`);
            if (saved) {
                setTimeout(() => {
                    this.video.currentTime = parseFloat(saved);
                }, 100);
            }
        }
    }
}
</script>

<style>
.video-player-root video::-webkit-media-controls { display: none !important; }
.video-player-root input[type="range"] {
    -webkit-appearance: none;
    background: transparent;
}
.video-player-root input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 12px;
    width: 12px;
    border-radius: 50%;
    background: white;
    cursor: pointer;
    margin-top: -4px;
}
</style>

