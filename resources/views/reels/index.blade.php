<x-main-layout>
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush

    <section class="relative min-h-screen bg-black text-white overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.25) 1px, transparent 0); background-size: 36px 36px;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-6">
            <div class="text-center mb-4 md:mb-6">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-fuchsia-400/30 bg-fuchsia-500/10 text-fuchsia-100 text-sm font-semibold">
                    Reels Feed
                </span>
                <h1 class="mt-4 text-2xl md:text-4xl font-bold bg-gradient-to-r from-white via-fuchsia-100 to-cyan-100 bg-clip-text text-transparent">
                    Watch products and concepts in motion
                </h1>
                <p class="mt-3 max-w-3xl mx-auto text-slate-300 text-xs md:text-sm">
                    Discover short-form videos from active products and concepts, open the original item, and interact with likes, comments, and shares.
                </p>
            </div>

            @if($reels->isEmpty())
                <div class="max-w-2xl mx-auto bg-white/10 border border-white/10 rounded-3xl p-10 text-center backdrop-blur-sm">
                    <div class="mx-auto w-20 h-20 rounded-full bg-white/10 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-fuchsia-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">No reels available yet</h2>
                    <p class="mt-3 text-slate-300">As soon as active products or concepts get a reel, they will appear here.</p>
                </div>
            @else
                <div id="reels-feed" class="snap-y snap-mandatory space-y-0 max-w-5xl mx-auto">
                    @foreach($reels as $reel)
                        <article
                            id="{{ $reel['key'] }}"
                            class="reel-card snap-start h-[100vh] flex items-center justify-center"
                            data-type="{{ $reel['type'] }}"
                            data-id="{{ $reel['id'] }}"
                            data-detail-url="{{ $reel['detail_url'] }}"
                            data-share-url="{{ $reel['detail_url'] }}#reel"
                            data-auth="{{ auth()->check() ? '1' : '0' }}"
                            data-login-url="{{ route('login') }}"
                        >
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="relative w-[350px] h-[560px] max-w-[calc(100vw-24px)] max-h-[calc(100vh-24px)] rounded-[2rem] overflow-hidden border border-white/10 shadow-[0_30px_80px_rgba(0,0,0,0.55)] bg-zinc-950">
                                    <video
                                        class="reel-video w-full h-full object-cover bg-black"
                                        src="{{ $reel['reel_url'] }}"
                                        poster="{{ $reel['poster_url'] }}"
                                        playsinline
                                        preload="metadata"
                                        loop
                                    ></video>

                                    <button type="button" class="reel-play-toggle absolute inset-0 z-[5]" aria-label="Toggle playback"></button>

                                    <div class="reel-play-indicator absolute inset-0 z-[6] flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-200">
                                        <div class="w-20 h-20 rounded-full bg-black/45 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-2xl">
                                            <svg class="reel-play-icon w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-black/10 pointer-events-none"></div>

                                    <div class="absolute inset-x-0 bottom-0 p-4 md:p-5 z-10">
                                        <div class="pr-[96px]">
                                            <div>
                                                <div class="flex items-center gap-3 mb-3">
                                                    <div class="w-11 h-11 rounded-full overflow-hidden bg-white/10 border border-white/10 flex items-center justify-center text-lg font-bold text-white">
                                                        @if($reel['owner_avatar'])
                                                            <img src="{{ $reel['owner_avatar'] }}" alt="{{ $reel['owner_name'] }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ \Illuminate\Support\Str::substr($reel['owner_name'] ?? 'C', 0, 1) }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-white text-sm">{{ $reel['owner_name'] }}</p>
                                                        <p class="text-xs text-slate-300">{{ $reel['source_label'] }}</p>
                                                    </div>
                                                </div>
                                                <h2 class="text-lg font-bold text-white leading-tight">{{ $reel['name'] }}</h2>
                                                @if($reel['description'])
                                                    <p class="mt-1.5 text-sm text-slate-200/90 leading-relaxed">{{ \Illuminate\Support\Str::limit($reel['description'], 72) }}</p>
                                                @endif
                                                <p class="mt-2 text-sm font-medium text-white/90">#{{ \Illuminate\Support\Str::slug($reel['name'], '_') }} #{{ \Illuminate\Support\Str::slug($reel['source_label'], '_') }}</p>
                                                <p class="mt-2 text-sm text-slate-200 flex items-center gap-2">
                                                    <span>🎵</span>
                                                    <span>Original Sound - {{ $reel['owner_name'] }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 flex flex-col items-center gap-3.5 z-30">
                                            <button type="button" class="reel-like-btn flex flex-col items-center gap-1.5 text-white {{ $reel['is_liked'] ? 'text-pink-300' : '' }}">
                                                <span class="w-12 h-12 rounded-full bg-black/45 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6 {{ $reel['is_liked'] ? 'text-pink-400' : 'text-white' }}" fill="{{ $reel['is_liked'] ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-semibold">Like</span>
                                                <span class="reel-likes-count text-xs font-bold">{{ $reel['likes_count'] }}</span>
                                            </button>

                                            <button type="button" class="reel-comments-toggle flex flex-col items-center gap-1.5 text-white">
                                                <span class="w-12 h-12 rounded-full bg-black/45 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-semibold">Comment</span>
                                                <span class="reel-comments-count text-xs font-bold">{{ $reel['comments_count'] }}</span>
                                            </button>

                                            <button type="button" class="reel-share-btn flex flex-col items-center gap-1.5 text-white">
                                                <span class="w-12 h-12 rounded-full bg-black/45 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C9.046 12.553 9.954 12 11 12c1.046 0 1.954.553 2.316 1.342m-4.632 0A2.5 2.5 0 116.5 18a2.5 2.5 0 012.184-4.658zm4.632 0A2.5 2.5 0 1117.5 18a2.5 2.5 0 01-2.184-4.658zM12 6.5A2.5 2.5 0 109.5 4 2.5 2.5 0 0012 6.5z" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-semibold">Share</span>
                                                <span class="reel-shares-count text-xs font-bold">{{ $reel['shares_count'] }}</span>
                                            </button>

                                            <button type="button" class="reel-more-btn flex flex-col items-center gap-1.5 text-white">
                                                <span class="w-12 h-12 rounded-full bg-black/45 backdrop-blur-sm border border-white/10 flex items-center justify-center shadow-lg">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6h.01M12 12h.01M12 18h.01" />
                                                    </svg>
                                                </span>
                                                <span class="text-[11px] font-semibold">More</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="reel-comments-source hidden">
                                        @forelse($reel['comments'] as $comment)
                                            <div class="reel-comment-item rounded-xl bg-black/20 border border-white/5 p-3">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-white/10 flex items-center justify-center text-sm font-bold text-white">
                                                        @if($comment['user']['avatar_media'] || $comment['user']['avatar'])
                                                            <img src="{{ $comment['user']['avatar_media'] ?: $comment['user']['avatar'] }}" alt="{{ $comment['user']['name'] }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ \Illuminate\Support\Str::substr($comment['user']['name'] ?? 'U', 0, 1) }}
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-semibold text-white">{{ $comment['user']['name'] }}</p>
                                                        <p class="text-xs text-slate-400">{{ $comment['created_at'] }}</p>
                                                    </div>
                                                </div>
                                                <p class="text-sm text-slate-200 leading-relaxed">{{ $comment['comment'] }}</p>
                                            </div>
                                        @empty
                                            <div class="rounded-xl border border-dashed border-white/10 p-4 text-center text-sm text-slate-400">
                                                No comments yet. Start the conversation.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div id="reel-comments-sheet" class="fixed inset-0 z-[120] hidden">
                    <div class="reel-comments-backdrop absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
                    <div class="reel-comments-panel absolute inset-x-0 bottom-0 mx-auto w-full max-w-2xl max-h-[82vh] rounded-t-[2rem] border border-white/10 bg-zinc-950 shadow-2xl overflow-hidden">
                        <div class="px-5 pt-4 pb-3 border-b border-white/10 bg-white/5">
                            <div class="w-14 h-1.5 rounded-full bg-white/20 mx-auto mb-4"></div>
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-white font-bold text-xl">Comments</h3>
                                    <p class="reel-sheet-title text-sm text-slate-400 mt-1"></p>
                                </div>
                                <button type="button" class="reel-comments-close inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="p-4 md:p-5 flex flex-col h-[calc(82vh-92px)]">
                            <div class="reel-comments-list space-y-3 overflow-y-auto pr-1 flex-1 min-h-0"></div>

                            <form class="reel-comment-form mt-4" autocomplete="off">
                                <label class="block text-sm font-semibold text-slate-200 mb-2">Add a comment</label>
                                <div class="flex gap-3">
                                    <textarea name="comment" rows="3" maxlength="1000" placeholder="Write something about this reel..." class="reel-comment-input flex-1 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder-slate-400 focus:ring-2 focus:ring-fuchsia-500 focus:border-fuchsia-500"></textarea>
                                    <button type="submit" class="shrink-0 inline-flex items-center gap-2 self-end px-5 py-3 rounded-2xl bg-gradient-to-r from-fuchsia-500 to-cyan-500 text-white font-semibold hover:from-fuchsia-600 hover:to-cyan-600 transition-all duration-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-9.193-5.107A1 1 0 004 6.94v10.12a1 1 0 001.559.833l9.193-5.106a1 1 0 000-1.719z" />
                                        </svg>
                                        Send
                                    </button>
                                </div>
                                <p class="reel-form-message mt-2 text-sm text-slate-400"></p>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @push('scripts')
        <script>
            (() => {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const cards = Array.from(document.querySelectorAll('.reel-card'));
                const commentsSheet = document.getElementById('reel-comments-sheet');
                const commentsBackdrop = commentsSheet?.querySelector('.reel-comments-backdrop');
                const commentsClose = commentsSheet?.querySelector('.reel-comments-close');
                const commentsList = commentsSheet?.querySelector('.reel-comments-list');
                const commentsTitle = commentsSheet?.querySelector('.reel-sheet-title');
                const commentsForm = commentsSheet?.querySelector('.reel-comment-form');
                const commentsInput = commentsForm?.querySelector('.reel-comment-input');
                const commentsMessage = commentsSheet?.querySelector('.reel-form-message');
                let activeCommentsCard = null;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        const video = entry.target.querySelector('.reel-video');
                        const indicator = entry.target.querySelector('.reel-play-indicator');
                        const icon = entry.target.querySelector('.reel-play-icon');
                        if (!video) {
                            return;
                        }

                        if (entry.isIntersecting && entry.intersectionRatio >= 0.6) {
                            document.querySelectorAll('.reel-video').forEach((item) => {
                                if (item !== video) {
                                    item.pause();
                                }
                            });
                            video.play().catch(() => {});
                            indicator?.classList.add('opacity-0');
                            indicator?.classList.remove('opacity-100');
                            icon.innerHTML = '<path d="M8 5v14l11-7z" />';
                        } else {
                            video.pause();
                            indicator?.classList.remove('opacity-0');
                            indicator?.classList.add('opacity-100');
                            icon.innerHTML = '<path d="M8 5v14l11-7z" />';
                        }
                    });
                }, { threshold: [0.6] });

                cards.forEach((card) => observer.observe(card));

                const requireAuth = (card) => {
                    if (card.dataset.auth === '1') {
                        return true;
                    }

                    window.location.href = card.dataset.loginUrl;
                    return false;
                };

                const postJson = async (url, payload = {}) => {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw data;
                    }

                    return data;
                };

                const createCommentMarkup = (comment) => {
                    const avatar = comment.user.avatar_media || comment.user.avatar;
                    const initial = (comment.user.name || 'U').charAt(0).toUpperCase();

                    return `
                        <div class="rounded-xl bg-black/20 border border-white/5 p-3">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-full overflow-hidden bg-white/10 flex items-center justify-center text-sm font-bold text-white">
                                    ${avatar ? `<img src="${avatar}" alt="${comment.user.name}" class="w-full h-full object-cover">` : initial}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">${comment.user.name || 'User'}</p>
                                    <p class="text-xs text-slate-400">${comment.created_at}</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-200 leading-relaxed"></p>
                        </div>
                    `;
                };

                const syncSheetCommentsToCard = (card) => {
                    const cardList = card.querySelector('.reel-comments-source');
                    if (!cardList || !commentsList) {
                        return;
                    }

                    cardList.innerHTML = commentsList.innerHTML;
                };

                const openCommentsSheet = (card) => {
                    if (!commentsSheet || !commentsList) {
                        return;
                    }

                    activeCommentsCard = card;
                    commentsTitle.textContent = card.querySelector('h2')?.textContent || 'Reel comments';
                    commentsList.innerHTML = card.querySelector('.reel-comments-source')?.innerHTML || '';
                    commentsMessage.textContent = '';
                    commentsMessage.className = 'reel-form-message mt-2 text-sm text-slate-400';
                    commentsSheet.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    setTimeout(() => commentsInput?.focus(), 50);
                };

                const closeCommentsSheet = () => {
                    if (!commentsSheet) {
                        return;
                    }

                    commentsSheet.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    activeCommentsCard = null;
                };

                cards.forEach((card) => {
                    const type = card.dataset.type;
                    const id = card.dataset.id;
                    const likeBtn = card.querySelector('.reel-like-btn');
                    const shareBtn = card.querySelector('.reel-share-btn');
                    const moreButtons = card.querySelectorAll('.reel-more-btn');
                    const playToggle = card.querySelector('.reel-play-toggle');
                    const video = card.querySelector('.reel-video');
                    const playIndicator = card.querySelector('.reel-play-indicator');
                    const playIcon = card.querySelector('.reel-play-icon');

                    likeBtn?.addEventListener('click', async () => {
                        if (!requireAuth(card)) {
                            return;
                        }

                        try {
                            const data = await postJson(`/reels/${type}/${id}/like`);
                            const icon = likeBtn.querySelector('svg');
                            icon?.setAttribute('fill', data.liked ? 'currentColor' : 'none');
                            icon?.classList.toggle('text-pink-400', data.liked);
                            likeBtn.classList.toggle('text-pink-300', data.liked);
                            likeBtn.querySelector('.reel-likes-count').textContent = data.likes_count;
                        } catch (error) {
                            commentsMessage.textContent = error.message || 'Unable to update like right now.';
                            commentsMessage.className = 'reel-form-message mt-2 text-sm text-red-300';
                        }
                    });

                    playToggle?.addEventListener('click', () => {
                        if (!video) {
                            return;
                        }

                        if (video.paused) {
                            document.querySelectorAll('.reel-video').forEach((item) => {
                                if (item !== video) {
                                    item.pause();
                                }
                            });

                            video.play().catch(() => {});
                            playIndicator?.classList.remove('opacity-100');
                            playIndicator?.classList.add('opacity-0');
                            playIcon.innerHTML = '<path d="M8 5v14l11-7z" />';
                        } else {
                            video.pause();
                            playIndicator?.classList.remove('opacity-0');
                            playIndicator?.classList.add('opacity-100');
                            playIcon.innerHTML = '<path d="M8 5v14l11-7z" />';
                        }
                    });

                    shareBtn?.addEventListener('click', async () => {
                        const shareUrl = card.dataset.shareUrl;

                        try {
                            if (navigator.share) {
                                await navigator.share({ url: shareUrl, title: card.querySelector('h2')?.textContent || 'Reel' });
                            } else if (navigator.clipboard) {
                                await navigator.clipboard.writeText(shareUrl);
                            }

                            if (card.dataset.auth === '1') {
                                const data = await postJson(`/reels/${type}/${id}/share`);
                                shareBtn.querySelector('.reel-shares-count').textContent = data.shares_count;
                            }

                            commentsMessage.textContent = 'Link shared successfully.';
                            commentsMessage.className = 'reel-form-message mt-2 text-sm text-emerald-300';
                        } catch (error) {
                            if (error && error.success === false) {
                                commentsMessage.textContent = error.message || 'Unable to share right now.';
                            } else {
                                commentsMessage.textContent = 'Share cancelled or unavailable on this device.';
                            }
                            commentsMessage.className = 'reel-form-message mt-2 text-sm text-slate-400';
                        }
                    });

                    moreButtons.forEach((button) => {
                        button.addEventListener('click', () => {
                            window.location.href = card.dataset.detailUrl;
                        });
                    });

                    card.querySelectorAll('.reel-comments-toggle').forEach((button) => {
                        button.addEventListener('click', () => {
                            openCommentsSheet(card);
                        });
                    });

                    video?.addEventListener('play', () => {
                        playIndicator?.classList.remove('opacity-100');
                        playIndicator?.classList.add('opacity-0');
                        playIcon.innerHTML = '<path d="M8 5v14l11-7z" />';
                    });

                    video?.addEventListener('pause', () => {
                        playIndicator?.classList.remove('opacity-0');
                        playIndicator?.classList.add('opacity-100');
                        playIcon.innerHTML = '<path d="M8 5v14l11-7z" />';
                    });
                });

                commentsBackdrop?.addEventListener('click', closeCommentsSheet);
                commentsClose?.addEventListener('click', closeCommentsSheet);

                commentsForm?.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    if (!activeCommentsCard || !requireAuth(activeCommentsCard)) {
                        return;
                    }

                    const type = activeCommentsCard.dataset.type;
                    const id = activeCommentsCard.dataset.id;
                    const value = commentsInput.value.trim();

                    if (!value) {
                        commentsMessage.textContent = 'Please write a comment first.';
                        commentsMessage.className = 'reel-form-message mt-2 text-sm text-red-300';
                        return;
                    }

                    try {
                        const data = await postJson(`/reels/${type}/${id}/comments`, { comment: value });
                        const emptyState = commentsList?.querySelector('.border-dashed');
                        if (emptyState) {
                            emptyState.remove();
                        }

                        const wrapper = document.createElement('div');
                        wrapper.innerHTML = createCommentMarkup(data.comment).trim();
                        wrapper.querySelector('p.text-sm.text-slate-200.leading-relaxed').textContent = data.comment.comment;
                        wrapper.firstElementChild.classList.add('reel-comment-item');
                        commentsList?.prepend(wrapper.firstElementChild);
                        activeCommentsCard.querySelector('.reel-comments-count').textContent = data.comments_count;
                        syncSheetCommentsToCard(activeCommentsCard);
                        commentsInput.value = '';
                        commentsMessage.textContent = 'Comment added.';
                        commentsMessage.className = 'reel-form-message mt-2 text-sm text-emerald-300';
                    } catch (error) {
                        const message = error.errors?.comment?.[0] || error.message || 'Unable to post your comment.';
                        commentsMessage.textContent = message;
                        commentsMessage.className = 'reel-form-message mt-2 text-sm text-red-300';
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeCommentsSheet();
                    }
                });
            })();
        </script>
    @endpush
</x-main-layout>
