<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('おすすめ本の返信') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- 元の投稿情報 -->
                    <div class="mb-6 p-4 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-900">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-bold">{{ $post->user->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <p>{{ $post->content }}</p>
                        </div>
                        <div class="mt-2">
                            @foreach ($post->post_tags as $tag)
                                <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">
                                    #{{ $tag->tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- 返信者情報 -->
                    <div class="mb-6">
                        <p class="font-semibold">返信者: {{ Auth::user()->name }}</p>
                    </div>

                    <!-- 返信フォーム -->
                    <form action="{{ route('recommendation.store', $post) }}" method="POST" id="recommendationForm">
                        @csrf

                        <!-- 本の検索フォーム -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold mb-4">おすすめの本を検索</h3>
                            <div class="relative">
                                <div class="flex items-center w-full relative">
                                    <input type="text" id="bookSearch" class="input input-bordered w-full pr-10" placeholder="本のタイトルや著者名を入力（3文字以上）">
                                    <div class="absolute right-3">
                                        <span id="searchLoading" class="loading loading-spinner loading-sm text-info hidden"></span>
                                </div>
                            </div>

                        <!-- 検索結果表示エリア -->
                        <div id="searchResults" class="absolute z-10 mt-1 w-full bg-base-100 rounded-lg shadow-lg max-h-[70vh] overflow-y-auto hidden">
                            <div class="p-4 text-sm text-gray-500" id="initialMessage">
                                検索するには3文字以上入力してください
                            </div>
                            <div class="grid grid-cols-1 gap-4 p-4" id="resultsContainer">
                                <!-- 検索結果がここに表示されます -->
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- 選択された本の情報 -->
                        <div id="selectedBook" class="mb-6 p-4 border dark:border-gray-700 rounded-lg hidden">
                            <div class="flex flex-col md:flex-row gap-4">
                                <div class="w-full md:w-1/4">
                                    <img id="bookThumbnail" src="" alt="本の表紙" class="w-full rounded-lg">
                            </div>
                            <div class="w-full md:w-3/4">
                                <h3 id="bookTitle" class="text-lg font-bold"></h3>
                                <p id="bookAuthor" class="text-sm mb-2"></p>
                                <p id="bookId" class="text-xs text-gray-500 mb-2"></p>
                                <p id="bookDescription" class="text-sm"></p>
                            </div>
                        </div>
                    </div>

                        <!-- 隠しフィールド - 選択された本の情報 -->
                        <input type="hidden" name="book_title" id="book_title_input">
                        <input type="hidden" name="book_author" id="book_author_input">
                        <input type="hidden" name="book_thumbnail" id="book_thumbnail_input">
                        <input type="hidden" name="book_description" id="book_description_input">
                        <input type="hidden" name="book_id" id="book_id_input"> <!-- book_id -->

                        <!-- コメント入力エリア -->
                        <div class="form-control mb-6">
                            <label for="comment" class="label">
                                <span class="label-text">この本をおすすめする理由 (投稿者にはおすすめする理由のみが表示されます。本の情報は開示されません。)</span>
                            </label>
                            <textarea name="comment" id="comment" rows="6" class="textarea textarea-bordered w-full" placeholder="この本の魅力や、投稿者さんの悩みに対して、どのように役立つと思うかを書いてください" required></textarea>
                            @error('comment')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

           
                        <!-- 送信ボタン -->
                        <div class="flex justify-end" style="gap: 15px;">
                            <a href="{{ route('post.show', $post) }}" class="btn bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white">キャンセル</a>
                            
                            <button type="submit" class="btn bg-[#30466f] hover:bg-[#30466f]/90 border-[#30466f] text-white" id="submitButton" >返信を投稿</button>
                        </div>
                        </form>


    <!-- Google Books API 連携用のJavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookSearch = document.getElementById('bookSearch');
            const searchResults = document.getElementById('searchResults');
            const resultsContainer = document.getElementById('resultsContainer');
            const initialMessage = document.getElementById('initialMessage');
            const searchLoading = document.getElementById('searchLoading');
            const selectedBook = document.getElementById('selectedBook');
            const submitButton = document.getElementById('submitButton');

            // 本の情報を格納する隠しフィールド
            const bookTitleInput = document.getElementById('book_title_input');
            const bookAuthorInput = document.getElementById('book_author_input');
            const bookThumbnailInput = document.getElementById('book_thumbnail_input');
            const bookDescriptionInput = document.getElementById('book_description_input');
            const bookIdInput = document.getElementById('book_id_input');
            
            // 選択された本の表示エリア
            const bookThumbnail = document.getElementById('bookThumbnail');
            const bookTitle = document.getElementById('bookTitle');
            const bookAuthor = document.getElementById('bookAuthor');
            const bookId = document.getElementById('bookIdDisplay'); // この行を追加
            const bookDescription = document.getElementById('bookDescription');

            let searchTimeout = null;
            let lastQuery = '';

            // 検索ボックスにフォーカスが当たったとき
                bookSearch.addEventListener('focus', function() {
                if (selectedBook.classList.contains('hidden')) {
                    searchResults.classList.remove('hidden');
                }
            });
    
            // 検索ボックス外をクリックしたとき
                document.addEventListener('click', function(e) {
                if (!bookSearch.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });

            // 検索ボックス入力時の処理（デバウンス処理）
            bookSearch.addEventListener('input', function() {
            const query = this.value.trim();
        
        // クエリが3文字未満の場合
        if (query.length < 3) {
            initialMessage.textContent = "検索するには3文字以上入力してください";
            initialMessage.classList.remove('hidden');
            resultsContainer.classList.add('hidden');
            searchResults.classList.remove('hidden');
            return;
        }
        
        // 前回と同じクエリの場合は処理しない
        if (query === lastQuery) return;
        lastQuery = query;
        
        // 既存のタイムアウトをクリア
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        // ローディング表示
        searchLoading.classList.remove('hidden');
        
        // 少し待ってからAPIリクエスト（タイピング中の過剰なリクエストを防止。0.5秒）
        searchTimeout = setTimeout(function() {
            fetchBooks(query);
        }, 500);
    });

    // Google Books APIから本を検索する関数
    function fetchBooks(query) {
        fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=12`) //?q=${encodeURIComponent(query)} は検索クエリのパラメータ（ユーザーが入力した検索語）
            .then(response => response.json())
            .then(data => {
                // ローディング非表示
                searchLoading.classList.add('hidden');
                
                // 結果表示エリアをクリア
                resultsContainer.innerHTML = '';
                
                if (data.items && data.items.length > 0) {
                    // 検索結果がある場合
                    initialMessage.classList.add('hidden');
                    resultsContainer.classList.remove('hidden');
                    
                    data.items.forEach(book => {
                        const volumeInfo = book.volumeInfo;
                        const title = volumeInfo.title || '(タイトルなし)';
                        const authors = volumeInfo.authors ? volumeInfo.authors.join(', ') : '(著者不明)';
                        const thumbnail = volumeInfo.imageLinks ? volumeInfo.imageLinks.thumbnail : '/images/no-cover.png';
                        const description = volumeInfo.description || '説明はありません';
                        const bookId = book.id;
                        
                        // 検索結果アイテムの作成
                        const bookItem = document.createElement('div');
                        bookItem.className = 'flex items-center gap-3 p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg cursor-pointer transition-colors duration-200';
                        bookItem.innerHTML = `
                            <div class="w-12 h-16 flex-shrink-0">
                                <img src="${thumbnail}" alt="${title}" class="w-full h-full object-cover rounded">
                            </div>
                            <div class="flex-grow overflow-hidden">
                                <h4 class="font-medium text-sm line-clamp-1">${title}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1">${authors}</p>
                            </div>
                        `;

                        // クリックイベント
                        bookItem.addEventListener('click', function() {
                            selectBook(title, authors, thumbnail, description, bookId);
                            searchResults.classList.add('hidden');
                        });
                        
                        resultsContainer.appendChild(bookItem);
                    });
                    
                } else {
                    // 検索結果がない場合
                    initialMessage.textContent = "検索結果がありません";
                    initialMessage.classList.remove('hidden');
                    resultsContainer.classList.add('hidden');
                }
                
                // 検索結果を表示
                searchResults.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching books:', error);
                searchLoading.classList.add('hidden');
                initialMessage.textContent = "エラーが発生しました。もう一度お試しください。";
                initialMessage.classList.remove('hidden');
                resultsContainer.classList.add('hidden');
            });
    }
    
    // 本を選択する関数
    function selectBook(title, authors, thumbnail, description, bookId) {
        // 選択された本の情報を表示
        bookTitle.textContent = title;
        bookAuthor.textContent = authors;
        bookThumbnail.src = thumbnail;
        bookId.textContent = `ID: ${bookId}`;
        bookDescription.textContent = description;
        
        // 隠しフィールドに値をセット
        bookTitleInput.value = title;
        bookAuthorInput.value = authors;
        bookThumbnailInput.value = thumbnail;
        bookDescriptionInput.value = description;
        bookIdInput.value = bookId;
        
        // 選択された本の情報を表示
        selectedBook.classList.remove('hidden');
        
        // 送信ボタンを有効化
        submitButton.disabled = false;
    }
    
    // Enterキーでの送信を防止
    bookSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
});

            
        //     // 検索ボタンクリック時の処理
        //     searchButton.addEventListener('click', function() {
        //         const query = bookSearch.value.trim();
        //         if (query === '') return;
                
        //         // Google Books APIを呼び出す
        //         fetch(`https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(query)}&maxResults=9`)
        //             .then(response => response.json())
        //             .then(data => {
        //                 searchResults.innerHTML = '';
        //                 searchResults.classList.remove('hidden');
                        
        //                 if (data.items && data.items.length > 0) {
        //                     data.items.forEach(book => {
        //                         const volumeInfo = book.volumeInfo;
        //                         const title = volumeInfo.title || '(タイトルなし)';
        //                         const authors = volumeInfo.authors ? volumeInfo.authors.join(', ') : '(著者不明)';
        //                         const thumbnail = volumeInfo.imageLinks ? volumeInfo.imageLinks.thumbnail : '/images/no-cover.png';
        //                         const description = volumeInfo.description || '説明はありません';
        //                         const bookId = book.id; // ここでbook.idを取得する

        //                         const bookCard = document.createElement('div');
        //                         bookCard.className = 'card bg-base-100 shadow-md hover:shadow-lg cursor-pointer';
        //                         bookCard.innerHTML = `
        //                             <figure class="p-4 h-48 flex items-center justify-center">
        //                                 <img src="${thumbnail}" alt="${title}" class="max-h-full">
        //                             </figure>
        //                             <div class="card-body p-4">
        //                                 <h3 class="card-title text-sm line-clamp-2">${title}</h3>
        //                                 <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1">${authors}</p>
        //                             </div>
        //                         `;
                                
        //                         bookCard.addEventListener('click', function() {
        //                             // 選択された本の情報を表示
        //                             bookTitle.textContent = title;
        //                             bookAuthor.textContent = authors;
        //                             bookThumbnail.src = thumbnail;
        //                             bookId.textContent = `ID: ${bookId}`; // 追加: 本のID表示
        //                             bookDescription.textContent = description;

        //                             // デバッグコードをここに追加
        //                             console.log("Book ID being set:", bookId);
        //                             console.log("book_id_input element:", document.getElementById('book_id_input'));
                                    
        //                             // 隠しフィールドに値をセット
        //                             bookTitleInput.value = title;
        //                             bookAuthorInput.value = authors;
        //                             bookThumbnailInput.value = thumbnail;
        //                             bookDescriptionInput.value = description;
        //                             document.getElementById('book_id_input').value = bookId; //book_id をセット

        //                             // 値がセットされたか確認するデバッグコード
        //                             console.log("book_id_input element value after setting:", document.getElementById('book_id_input').value);
                                    
        //                             // 選択された本の情報を表示し、検索結果を非表示に
        //                             selectedBook.classList.remove('hidden');
        //                             searchResults.classList.add('hidden');
                                    
        //                             // 送信ボタンを有効化
        //                             submitButton.disabled = false;
        //                         });
                                
        //                         searchResults.appendChild(bookCard);
        //                     });
        //                 } else {
        //                     searchResults.innerHTML = '<p class="text-center p-4">検索結果がありません</p>';
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error('Error fetching books:', error);
        //                 searchResults.innerHTML = '<p class="text-center p-4">エラーが発生しました。もう一度お試しください。</p>';
        //             });
        //     });
            
        //     // Enterキーで検索を実行
        //     bookSearch.addEventListener('keypress', function(e) {
        //         if (e.key === 'Enter') {
        //             e.preventDefault();
        //             searchButton.click();
        //         }
        //     });
        // });
    </script>
</x-app-layout>