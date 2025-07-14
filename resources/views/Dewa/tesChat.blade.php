<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Chat App - Bubble Action Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background: #f0f2f5;
            overflow: hidden;
        }


        body {
            min-height: 100vh;
            min-width: 100vw;
            font-family: 'Inter', Arial, sans-serif;
        }

        .chat-app {
            width: 100vw;
            height: 100vh;
            display: flex;
            background: #f0f2f5;
        }

        .sidebar {
            width: 340px;
            max-width: 100vw;
            background: #fff;
            border-right: 1px solid #e3e6ea;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar-header {
            /* padding: 1rem; */
            border-bottom: 1px solid #e3e6ea;
            background: #f7f8fa;
            min-height: 72px !important;
            padding: 1rem 1.6rem;
            gap: 1rem;
            border-bottom: 1px solid #e3e6ea;

        }

        .sidebar-search {
            padding: 0.75rem 1rem;
            background: #fff;
            border-bottom: 1px solid #e3e6ea;
        }

        .sidebar-people {
            flex: 1 1 auto;
            overflow-y: auto;
            padding-bottom: 1rem;
            background: #fff;
        }

        .person {
            display: flex;
            align-items: center;
            padding: 0.7rem 1.2rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f3f3;
            gap: 1rem;
            position: relative;
        }

        .person.active,
        .person:hover {
            background: #e7f1ff;
        }

        .person-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #d6dbdf;
            object-fit: cover;
            flex-shrink: 0;
        }

        .person-details {
            min-width: 0;
            flex: 1 1 auto;
        }

        .person-details strong {
            display: block;
            font-size: 1rem;
            color: #23272f;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .person-details small {
            color: #8a9099;
            font-size: 0.93rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-main {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background: #f0f2f5;
        }

        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            padding: 1rem 1.6rem;
            gap: 1rem;
            border-bottom: 1px solid #e3e6ea;
            min-height: 72px;
        }

        .chat-header .chat-person {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chat-header .person-img {
            width: 44px;
            height: 44px;
        }

        .chat-header span {
            font-weight: 600;
            font-size: 1.11rem;
            color: #23272f;
        }

        .chat-header .btn {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }



        .chat-search {
            background: #f7f8fa;
            padding: 0.7rem 1.6rem;
            border-bottom: 1px solid #e3e6ea;
        }

        .chat-body {
            /* background-color: #006cff !important; */
            /* height: 50vh !important; */
            /* max-height: 50vh !important; */
            flex: 1 1 auto;
            padding: 2rem 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background: #f7faff;
            position: relative;
        }

        .chat-message {
            display: flex;
            align-items: flex-end;
            gap: 0.6rem;
            position: relative;
            max-width: 100%;
        }

        .chat-message.user {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .chat-message .person-img {
            width: 36px;
            height: 36px;
        }

        .chat-message .msg {
            background: #fff;
            color: #23272f;
            border-radius: 1.3rem;
            padding: 1rem 1.25rem;
            max-width: 58vw;
            min-width: 60px;
            box-shadow: 0 2px 7px rgba(0, 0, 0, 0.04);
            position: relative;
            font-size: 1.03rem;
            word-break: break-word;
            cursor: pointer;
        }

        .chat-message.user .msg {
            background: #006cff;
            color: #fff;
            border-bottom-right-radius: 0.4rem;
            margin-left: 0.1rem;
        }

        .chat-message {
            img {
                width: 36px;
                height: 36px;
                margin: 0;
                padding: 0;
            }
        }

        .chat-message.user {
            justify-content: end !important;
            width: 100%;


        }

        .chat-message.other {
            justify-content: start;
            width: 100%;
        }

        .chat-message.other .msg {
            background: #f0f2f5;
            color: #222;
            border-bottom-left-radius: 0.4rem;
            margin-right: 0.1rem;
        }

        .msg .replied-to {
            background: #e6eaff;
            color: #414561;
            padding: 0.38rem 0.85rem;
            border-radius: 0.7rem 0.7rem 0.7rem 0.2rem;
            margin-bottom: 0.42rem;
            font-size: 0.96em;
            display: inline-block;
            font-style: italic;
        }

        .chat-footer {
            padding: 1.1rem 1.6rem;
            background: #fff;
            border-top: 1px solid #e3e6ea;
            display: flex;
            gap: 0.7rem;
            align-items: center;
            min-height: 80px;
            max-height: fit-content;
            z-index: 2;
        }

        .chat-footer input[type="text"] {
            flex: 1 1 auto;
            border-radius: 1.2rem;
            font-size: 1.04rem;
            padding-left: 1.1rem;
            padding-right: 1.1rem;
        }

        .file-upload-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .file-upload-label {
            cursor: pointer;
        }

        .file-type-select {
            max-width: 95px;
            font-size: 0.96rem;
        }

        .file-preview {
            font-size: 0.97rem;
            margin-left: 8px;
            color: #2a4365;
            background: #eaf6ff;
            border-radius: 10px;
            padding: 2px 10px;
        }

        .remove-file {
            color: #db2222;
            margin-left: 5px;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* Bubble Action Menu */
        .bubble-menu {
            position: absolute;
            left: 0;
            top: 100%;
            min-width: 120px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            z-index: 100;
            margin-top: 8px;
            padding: 6px 0;
            border: 1px solid #e8eaf1;
            animation: fadein .16s;
        }

        @keyframes fadein {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bubble-menu-btn {
            width: 100%;
            text-align: left;
            padding: 10px 20px;
            border: none;
            background: none;
            color: #293048;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.1s;
        }

        .bubble-menu-btn:hover {
            background: #f0f4ff;
            color: #175fff;
        }

        .chat-message.user .bubble-menu {
            left: auto;
            right: 0;
        }

        @media (max-width: 900px) {
            .sidebar {
                width: 88px;
                min-width: 88px;
            }

            .sidebar-header,
            .sidebar-search {
                display: none;
            }

            .person-details {
                display: none;
            }

            .person-img {
                margin: auto;
            }

            .sidebar-people {
                padding: 0;
            }

            .chat-header,
            .chat-search,
            .chat-footer {
                padding-left: 0.7rem !important;
                padding-right: 0.7rem !important;
            }
        }

        @media (max-width: 650px) {

            .chat-app,
            .chat-main,
            html,
            body {
                min-width: 100vw;
                max-width: 100vw;
                width: 100vw !important;
            }

            .sidebar {
                display: none;
            }

            .chat-header,
            .chat-footer {
                min-width: 100vw;
                max-width: 100vw;
                width: 100vw;
            }

            .chat-main {
                width: 100% !important;
            }

            .chat-body .msg {
                max-width: 85vw !important;
            }
        }

        ::-webkit-scrollbar {
            width: 6px;
            background: #f0f2f5;
        }

        ::-webkit-scrollbar-thumb {
            background: #e6e7ee;
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="chat-app">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <strong style="font-size:1.16rem;color:#1565c0;">Chat List</strong>
            </div>
            <div class="sidebar-search">
                <input type="text" class="form-control" placeholder="Cari orang...">
            </div>
            <div class="sidebar-people">
                <div class="person active">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div class="person-details">
                        <strong>Agus Saputra</strong>
                        <small>Hai! Ada kabar baru?</small>
                    </div>
                </div>
                <div class="person">
                    <img src="https://randomuser.me/api/portraits/women/20.jpg" class="person-img" />
                    <div class="person-details">
                        <strong>Bella Wulandari</strong>
                        <small>Oke, nanti saya cek dulu ya</small>
                    </div>
                </div>
                <div class="person">
                    <img src="https://randomuser.me/api/portraits/men/22.jpg" class="person-img" />
                    <div class="person-details">
                        <strong>Rama Permana</strong>
                        <small>Terima kasih banyak!</small>
                    </div>
                </div>
                <div class="person">
                    <img src="https://randomuser.me/api/portraits/men/31.jpg" class="person-img" />
                    <div class="person-details">
                        <strong>Erik Pratama</strong>
                        <small>Sampai ketemu besok ya</small>
                    </div>
                </div>
                <div class="person">
                    <img src="https://randomuser.me/api/portraits/women/33.jpg" class="person-img" />
                    <div class="person-details">
                        <strong>Putri Anjani</strong>
                        <small>Sudah saya kirim emailnya</small>
                    </div>
                </div>
            </div>
        </div>
        <!-- MAIN CHAT -->
        <div class="chat-main">
            <div class="chat-header">
                <div class="chat-person">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <span>Agus Saputra</span>
                </div>
                <button class="btn btn-light" title="Lainnya">
                    <svg width="23" height="23" viewBox="0 0 16 16">
                        <circle cx="2.5" cy="8" r="1.5" />
                        <circle cx="8" cy="8" r="1.5" />
                        <circle cx="13.5" cy="8" r="1.5" />
                    </svg>
                </button>
            </div>
            <div class="chat-search">
                <input type="text" class="form-control" placeholder="Cari isi chat...">
            </div>
            <div class="chat-body">
                <!-- PENERIMA -->
                <div class="chat-message other">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div>
                        <div class="msg">Alhamdulillah, sudah hampir selesai kak 🙏</div>
                    </div>
                </div>
                <!-- PENGIRIM -->
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">Baik, nanti saya update lagi kalau sudah fix.</div>
                    </div>
                </div>
                <!-- PENERIMA -->
                <div class="chat-message other">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div>
                        <div class="msg">Oke siap, terima kasih yaa!</div>
                    </div>
                </div>
                <!-- PENGIRIM -->
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">🙏🙏🙏</div>
                    </div>
                </div>
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">Baik, nanti saya update lagi kalau sudah fix.</div>
                    </div>
                </div>
                <!-- PENERIMA -->
                <div class="chat-message other">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div>
                        <div class="msg">Oke siap, terima kasih yaa!</div>
                    </div>
                </div>
                <!-- PENGIRIM -->
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">🙏🙏🙏</div>
                    </div>
                </div>
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">Baik, nanti saya update lagi kalau sudah fix.</div>
                    </div>
                </div>
                <!-- PENERIMA -->
                <div class="chat-message other">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div>
                        <div class="msg">Oke siap, terima kasih yaa!</div>
                    </div>
                </div>
                <!-- PENGIRIM -->
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">🙏🙏🙏</div>
                    </div>
                </div>
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div>
                        <div class="msg">Baik, nanti saya update lagi kalau sudah fix.</div>
                    </div>
                </div>
                <!-- PENERIMA -->
                <div class="chat-message other">
                    <img src="https://randomuser.me/api/portraits/men/12.jpg" class="person-img" />
                    <div>
                        <div class="msg">Oke siap, terima kasih yaa!</div>
                    </div>
                </div>
                <!-- PENGIRIM -->
                <div class="chat-message user">
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    <div class="d-flex flex-column gap-1 justify-content-end align-items-end">
                        <div class="d-flex justify-content-end mb-2">
                            <div
                                class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex align-items-center shadow-sm">
                                <i class="bi bi-file-earmark-pdf text-danger fs-3 me-3"></i>
                                <div>
                                    <div class="fw-semibold text-primary mb-2">Ariana.pdf</div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ asset('Dewa/file/beri kerja.pdf') }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Preview
                                        </a>
                                        <a href="{{ asset('Dewa/file/beri kerja.pdf') }}" download
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <img class="w-50 h-auto rounded-3" src="/Dewa/img/f4030acf172260f3241cad5f4527a7d8.jpg" alt="">
                        <div class="msg">🙏🙏🙏</div>
                    </div>
                </div>
            </div>
            <!-- CHAT FOOTER -->
            <form class="chat-footer" id="chat-footer">
                <div class="w-100">
                    <div class="d-flex align-items-center mb-2">
                        <div class="border-start border-4 border-primary ps-2 me-2 small text-secondary"
                            style="min-width: 0;">
                            <span class="d-block text-truncate" style="max-width: 250px;">
                                Silakan, produk yang mana ya?
                            </span>
                        </div>
                        <button class="btn btn-sm btn-close ms-auto" aria-label="Close"></button>
                    </div>
                    <input type="text" class="form-control" placeholder="Ketik pesan..." id="chatInput"
                        autocomplete="off" />
                </div>
                <button class="btn btn-primary px-4" type="submit" id="sendBtn">Kirim</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === File/Link Upload ===
        // const fileTypeSelect = document.getElementById('fileTypeSelect');
        // const fileInput = document.getElementById('fileInput');
        // const fileLabel = document.getElementById('fileLabel');
        // const linkInput = document.getElementById('linkInput');
        // const filePreview = document.getElementById('filePreview');
        // const removeFileBtn = document.getElementById('removeFileBtn');
        // let selectedFile = null;
        // let selectedLink = "";

        // function updateFileInput() {
        //     // Hide all
        //     fileInput.classList.add('d-none');
        //     fileLabel.classList.add('d-none');
        //     linkInput.classList.add('d-none');
        //     filePreview.classList.add('d-none');
        //     removeFileBtn.classList.add('d-none');
        //     fileInput.value = '';
        //     linkInput.value = '';
        //     selectedFile = null;
        //     selectedLink = "";

        //     // Type handling
        //     const val = fileTypeSelect.value;
        //     if (val === 'file') {
        //         fileInput.accept = ".jpg,.jpeg,.png,.pdf,.xlsx,.xls,.docx,.doc,.txt,.csv";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'image') {
        //         fileInput.accept = ".jpg,.jpeg,.png";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'pdf') {
        //         fileInput.accept = ".pdf";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'excel') {
        //         fileInput.accept = ".xlsx,.xls,.csv";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'word') {
        //         fileInput.accept = ".docx,.doc";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'txt') {
        //         fileInput.accept = ".txt";
        //         fileLabel.classList.remove('d-none');
        //     } else if (val === 'link') {
        //         linkInput.classList.remove('d-none');
        //     }
        // }

        // fileTypeSelect.addEventListener('change', updateFileInput);

        // file input change
        // fileInput.addEventListener('change', function () {
        //     if (fileInput.files && fileInput.files[0]) {
        //         selectedFile = fileInput.files[0];
        //         filePreview.textContent = selectedFile.name;
        //         filePreview.classList.remove('d-none');
        //         removeFileBtn.classList.remove('d-none');
        //     }
        // });

        // link input change
        // linkInput.addEventListener('input', function () {
        //     if (linkInput.value.length > 0) {
        //         selectedLink = linkInput.value;
        //         filePreview.textContent = selectedLink;
        //         filePreview.classList.remove('d-none');
        //         removeFileBtn.classList.remove('d-none');
        //     } else {
        //         filePreview.classList.add('d-none');
        //         removeFileBtn.classList.add('d-none');
        //     }
        // });

        // // remove file/link button
        // removeFileBtn.addEventListener('click', function () {
        //     selectedFile = null;
        //     selectedLink = "";
        //     fileInput.value = '';
        //     linkInput.value = '';
        //     filePreview.classList.add('d-none');
        //     removeFileBtn.classList.add('d-none');
        // });

        // // label click
        // fileLabel.addEventListener('click', function () {
        //     fileInput.click();
        // });

        // // Set default state
        // updateFileInput();

        // On submit: preview (tidak benar2 upload/simpan, hanya simulasi kirim)
        document.getElementById('chat-footer').addEventListener('submit', function (e) {
            e.preventDefault();
            const chatInput = document.getElementById('chatInput');
            let text = chatInput.value.trim();
            if (text === "" && !selectedFile && !selectedLink) return;

            let preview = "";
            // if (selectedFile) {
            //     preview = `<div class="mt-1"><span style="color:#f6ff;">📎 ${selectedFile.name}</span></div>`;
            // } else if (selectedLink) {
            // preview = `<div class="mt-1"><a href="${selectedLink}" target="_blank" style="color:#ffd600;">🔗 ${selectedLink}</a></div>`;
            // }

            // Tambahkan elemen chat baru
            const chatBody = document.querySelector('.chat-body');
            chatBody.insertAdjacentHTML('beforeend', `
                    <div class="chat-message user">
                        <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                        <div>
                        <div class="msg">${text}${preview}</div>
                        </div>
                    </div>
                    `);

            chatInput.value = "";
            // selectedFile = null;
            // selectedLink = "";
            // filePreview.classList.add('d-none');
            // removeFileBtn.classList.add('d-none');
            // fileInput.value = '';
            // linkInput.value = '';
            // updateFileInput();

            newest_chat();


            // Scroll ke elemen baru


            // Re-bind bubble event
            setTimeout(bubbleMenuBind, 100);
        });
        newest_chat();
        function newest_chat() {
            const chatBodies = document.querySelectorAll('.chat-message');
            const newest = chatBodies[chatBodies.length - 1]; // Elemen .chat-body terakhir
            if (newest) {
                console.log('msuk')
                newest.scrollIntoView({ behavior: "smooth" });
            }
            // console.log('newest', newest);
        }


        // ==== Bubble Menu ====
        function bubbleMenuBind() {
            document.querySelectorAll('.chat-message .msg').forEach(function (el) {
                el.onclick = function (e) {
                    e.stopPropagation();
                    removeBubbleMenu();
                    showBubbleMenu(el);
                };
            });
        }

        function showBubbleMenu(msgElem) {
            removeBubbleMenu();
            const bubble = document.createElement('div');
            bubble.className = 'bubble-menu';
            bubble.innerHTML = `
      <button class="bubble-menu-btn" onclick="alert('Fitur reply belum diaktifkan!')">Reply</button>
      <button class="bubble-menu-btn" onclick="alert('Fitur edit belum diaktifkan!')">Edit</button>
      <button class="bubble-menu-btn text-danger" onclick="bubbleDeleteMsg(this)">Delete</button>
    `;
            msgElem.parentElement.appendChild(bubble);

            // Close if click outside
            setTimeout(function () {
                window.addEventListener('click', removeBubbleMenu, { once: true });
            }, 50);
        }
        function removeBubbleMenu() {
            document.querySelectorAll('.bubble-menu').forEach(function (el) { el.remove(); });
        }
        function bubbleDeleteMsg(btn) {
            // Hapus bubble dan chat message parent
            const msgWrap = btn.closest('.chat-message');
            msgWrap.remove();
            removeBubbleMenu();
        }
        // Init on load
        bubbleMenuBind();
    </script>
</body>

</html>