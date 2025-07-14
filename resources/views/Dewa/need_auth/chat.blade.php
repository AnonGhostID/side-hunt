<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Chat - Side Hunt</title>

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
            /* max-height: 70vh !important; */
            flex: 1 1 auto;
            /* min-height: 70vh !important; */
            padding: 2rem 1.5rem;
            /* padding-top: 2rem; */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            background: #f7faff;
            position: relative;
        }

        .chat-inner {
            /* max-width: min-content !important; */
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

            >div {
                justify-content: end !important;
                align-items: end !important;
            }

            .previewFile {
                justify-content: end !important;
            }

            .preview {
                align-items: end !important;
            }

            .reply-preview {
                width: auto;
                display: flex;
                justify-content: start;
                align-items: start;
            }

            div>img {
                width: max-content;
            }


        }

        .chat-message.other {
            justify-content: start !important;
            width: 100%;

            >div {
                justify-content: start;
                align-items: start;
            }

            .previewFile {
                /* justify-content-end */
                justify-content: start;
            }

            .preview {
                align-items: start;
            }
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
            min-height: 120px;
            max-height: 200px;
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

        @media (max-width: 650px) {
            .sidebar {
                position: fixed;
                left: -100vw;
                /* Sembunyikan di luar layar */
                top: 0;
                width: 80vw;
                max-width: 350px;
                height: 100vh;
                transition: left 0.22s cubic-bezier(.46, .01, .32, 1);
                box-shadow: 3px 0 16px rgba(0, 0, 0, 0.13);
                z-index: 2001;
                display: block;
            }

            .sidebar.open {
                left: 0;
                /* Muncul slide-in */
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.15);
                z-index: 2000;
            }

            .sidebar.open+.sidebar-backdrop {
                display: block;
            }

            #openSidebarBtn {
                display: inline-flex !important;
            }
        }

        #openSidebarBtn {
            display: none;
        }

        .reply-preview {
            background: #f2f2f2;
            border-left: 3px solid #4F8CFF;
            padding: 6px 12px;
            margin-bottom: 6px;
            font-size: 0.95em;
            color: #555;
            border-radius: 6px;
            width: fit-content;
            /* margin-right: 10%; */
            /* margin-right: ; */
        }

        .reply-author {
            font-weight: bold;
            margin-right: 5px;
        }

        .btn-close-custom {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
            background: rgba(0, 0, 0, 0.05);
            border: none;
            border-radius: 50%;
            transition: background 0.15s, box-shadow 0.15s, color 0.15s;
            color: #6b7280;
            /* soft gray */
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            margin-left: auto;
        }

        .btn-close-custom:hover {
            background: #f87171;
            /* red-400 */
            color: white;
            box-shadow: 0 4px 16px rgba(248, 113, 113, 0.2);
        }

        .btn-close-custom svg {
            pointer-events: none;
        }
    </style>
</head>

@php
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
@endphp

<body>
    <div class="chat-app">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div
                class="sidebar-header d-flex align-items-center justify-content-between p-3 bg-white rounded-top shadow-sm">
                <span class="fw-bold fs-5 text-primary">Chat List</span>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.location.href='/'">
                    Kembali ke Beranda
                </button>
            </div>

            <div class="sidebar-search">
                <input type="text" class="form-control" oninput="search(this)" placeholder="Cari orang...">
            </div>
            <div class="sidebar-people">
                @if(count($all)>0)
                @forEach($all as $person)
                @if($person->counterpart_id!=session('account')->id)
                <div class="person the_person" onclick="window.location.href='/chat/{{{$person->counterpart_id}}}'">
                    @if($person->avatar_url!=null)
                    <img src="" class="person-img" />
                    @else
                    <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="29" rx="14.5" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.375 9.5C11.375 8.57174 11.7437 7.6815 12.4001 7.02513C13.0565 6.36875 13.9467 6 14.875 6C15.8033 6 16.6935 6.36875 17.3499 7.02513C18.0063 7.6815 18.375 8.57174 18.375 9.5C18.375 10.4283 18.0063 11.3185 17.3499 11.9749C16.6935 12.6313 15.8033 13 14.875 13C13.9467 13 13.0565 12.6313 12.4001 11.9749C11.7437 11.3185 11.375 10.4283 11.375 9.5ZM11.375 14.75C10.2147 14.75 9.10188 15.2109 8.28141 16.0314C7.46094 16.8519 7 17.9647 7 19.125C7 19.8212 7.27656 20.4889 7.76884 20.9812C8.26113 21.4734 8.92881 21.75 9.625 21.75H20.125C20.8212 21.75 21.4889 21.4734 21.9812 20.9812C22.4734 20.4889 22.75 19.8212 22.75 19.125C22.75 17.9647 22.2891 16.8519 21.4686 16.0314C20.6481 15.2109 19.5353 14.75 18.375 14.75H11.375Z"
                            fill="#1B4841" />
                    </svg>
                    @endif
                    <div class="person-details">
                        <strong class="person_nama">{{{$person->nama_user}}}</strong>
                        @if($person->contents!=null)
                        @if($person->sender==session('account')->id)
                        <small>me: {{{$person->contents}}}</small>
                        @else
                        <small>{{{$person->contents}}}</small>
                        @endif
                        @else
                        <small>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary">{{{$person->Lamaran_status}}}</span>
                            </div>
                        </small>
                        @endif
                    </div>
                </div>
                @else
                @endif
                @endforeach
                @else
                <div class="no chat w-100 h-100 d-flex justify-content-center align-content-center">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary">Belum ada history chat</span>
                </div>
            </div>
                @endif
            </div>
        </div>
        <div class="sidebar-backdrop"></div>

        <!-- MAIN CHAT -->
        <div class="chat-main">
            @if($target=='all')
            <div class="no chat w-100 h-100 d-flex justify-content-center align-content-center">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary">Silahkan Pilih Dengan Siapa anda ingin berkomunikasi</span>
                </div>
            </div>
            @elseif(count($all_chats)>0)
            <div class="chat-header">
                <!-- Ini tombol untuk buka sidebar di HP -->
                <button
                    class="btn btn-outline-primary d-flex d-sm-none d-md-none align-items-center gap-2 px-3 py-1 rounded-3 shadow-sm"
                    id="openSidebarBtn" type="button" title="Open Sidebar" style="font-size: 0.96rem;">
                    <i class="bi bi-list fs-5"></i>
                    <span class="d-none d-md-inline">Menu</span>
                </button>

                <div class="chat-person">
                    @if($user->avatar_url!=null)
                    <img src="" class="person-img" />
                    @else
                    <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="29" rx="14.5" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.375 9.5C11.375 8.57174 11.7437 7.6815 12.4001 7.02513C13.0565 6.36875 13.9467 6 14.875 6C15.8033 6 16.6935 6.36875 17.3499 7.02513C18.0063 7.6815 18.375 8.57174 18.375 9.5C18.375 10.4283 18.0063 11.3185 17.3499 11.9749C16.6935 12.6313 15.8033 13 14.875 13C13.9467 13 13.0565 12.6313 12.4001 11.9749C11.7437 11.3185 11.375 10.4283 11.375 9.5ZM11.375 14.75C10.2147 14.75 9.10188 15.2109 8.28141 16.0314C7.46094 16.8519 7 17.9647 7 19.125C7 19.8212 7.27656 20.4889 7.76884 20.9812C8.26113 21.4734 8.92881 21.75 9.625 21.75H20.125C20.8212 21.75 21.4889 21.4734 21.9812 20.9812C22.4734 20.4889 22.75 19.8212 22.75 19.125C22.75 17.9647 22.2891 16.8519 21.4686 16.0314C20.6481 15.2109 19.5353 14.75 18.375 14.75H11.375Z"
                            fill="#1B4841" />
                    </svg>
                    @endif
                    <span>{{{$user->nama}}}</span>
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
                @if((count($all_chats)>0 && ($all_chats[0]!=true || isset($all_chats[0]->id_chat))) || session('account')['role']!='admin')
                @forEach($all_chats as $chat)
                @if($chat->Lamaran_status!=null)
                <div class="alert {{{$chat->Lamaran_status == 'Gagal'?'alert-dark':'alert-info'}}} d-flex flex-column justify-content-center align-items-center mb-3 rounded-3 border-0 py-2"
                    role="alert" style="background-color: #e7f3fe;">
                    @if($chat->Lamaran_status == 'Gagal'||$chat->Lamaran_status == 'Gagal'||$chat->Lamaran_status ==
                    'ditolak'||$chat->Lamaran_status =='Lamaran Dihapus')
                    <span class="fw-semibold text-dark opacity-50 small">Chat Berakhir</span>
                    @else
                    @if($chat->Lamaran_status == 'Gagal'||$chat->Lamaran_status == 'ditolak'||$chat->Lamaran_status
                    =='Lamaran Dihapus')
                    <span class="fw-semibold text-dark opacity-50 small">Topik: </span>
                    @else
                    <span class="fw-semibold text-primary small">Topik: </span>
                    @endif
                    @endif
                    @if($chat->Lamaran_status == 'Gagal'||$chat->Lamaran_status == 'ditolak'||$chat->Lamaran_status
                    =='Lamaran Dihapus')
                    <span class="fw-semibold text-dark opacity-50 small">
                        @else
                        <span class="fw-semibold text-primary small">
                            @endif
                            {{{ $chat->nama }}}
                            (
                            {{{
                            $chat->Lamaran_status == 'tunda' ? 'Lamaran Masuk' :
                            ($chat->Lamaran_status == 'interview' ? 'Dalam masa Interview' :
                            ($chat->Lamaran_status == 'Menunggu Pekerjaan' ? 'Menunggu Bekerja' :
                            ($chat->Lamaran_status == 'Gagal' ? 'Lamaran Gagal' : $chat->Lamaran_status)))
                            }}}
                            )
                        </span>

                        <span>
                            @if($chat->Lamaran_status == 'Gagal'||$chat->Lamaran_status ==
                            'Gagal'||$chat->Lamaran_status == 'ditolak'||$chat->Lamaran_status =='Lamaran Dihapus')
                            <p class="fw-semibold text-dark opacity-50 small">{{{$chat->sent}}}</p>
                            @else
                            <p class="fw-semibold text-primary small">{{{$chat->sent}}}</p>
                            @endif
                        </span>

                </div>
                @else
                <div class="chat-message {{{$chat->sender==session('account')['id']?'user':'other'}}}"
                    data_id_references="{{{$chat->id_chat}}}">
                    @if($chat->sender==session('account')['id'])
                    @if(session('account')['avatar_url']!=null)
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    @else
                    <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="29" rx="14.5" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.375 9.5C11.375 8.57174 11.7437 7.6815 12.4001 7.02513C13.0565 6.36875 13.9467 6 14.875 6C15.8033 6 16.6935 6.36875 17.3499 7.02513C18.0063 7.6815 18.375 8.57174 18.375 9.5C18.375 10.4283 18.0063 11.3185 17.3499 11.9749C16.6935 12.6313 15.8033 13 14.875 13C13.9467 13 13.0565 12.6313 12.4001 11.9749C11.7437 11.3185 11.375 10.4283 11.375 9.5ZM11.375 14.75C10.2147 14.75 9.10188 15.2109 8.28141 16.0314C7.46094 16.8519 7 17.9647 7 19.125C7 19.8212 7.27656 20.4889 7.76884 20.9812C8.26113 21.4734 8.92881 21.75 9.625 21.75H20.125C20.8212 21.75 21.4889 21.4734 21.9812 20.9812C22.4734 20.4889 22.75 19.8212 22.75 19.125C22.75 17.9647 22.2891 16.8519 21.4686 16.0314C20.6481 15.2109 19.5353 14.75 18.375 14.75H11.375Z"
                            fill="#1B4841" />
                    </svg>
                    @endif
                    @else
                    @if($user->avatar_url!=null)
                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                    @else
                    <svg width="30" height="29" viewBox="0 0 30 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="30" height="29" rx="14.5" fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M11.375 9.5C11.375 8.57174 11.7437 7.6815 12.4001 7.02513C13.0565 6.36875 13.9467 6 14.875 6C15.8033 6 16.6935 6.36875 17.3499 7.02513C18.0063 7.6815 18.375 8.57174 18.375 9.5C18.375 10.4283 18.0063 11.3185 17.3499 11.9749C16.6935 12.6313 15.8033 13 14.875 13C13.9467 13 13.0565 12.6313 12.4001 11.9749C11.7437 11.3185 11.375 10.4283 11.375 9.5ZM11.375 14.75C10.2147 14.75 9.10188 15.2109 8.28141 16.0314C7.46094 16.8519 7 17.9647 7 19.125C7 19.8212 7.27656 20.4889 7.76884 20.9812C8.26113 21.4734 8.92881 21.75 9.625 21.75H20.125C20.8212 21.75 21.4889 21.4734 21.9812 20.9812C22.4734 20.4889 22.75 19.8212 22.75 19.125C22.75 17.9647 22.2891 16.8519 21.4686 16.0314C20.6481 15.2109 19.5353 14.75 18.375 14.75H11.375Z"
                            fill="#1B4841" />
                    </svg>
                    @endif
                    @endif

                    <div class="chat-inner d-flex flex-column align-items-start">
                        @if($chat->chat_references!=null)
                        <div class="reply-preview" data-references="{{{$chat->chat_references}}}">
                            <span class="reply-text">{{{$chat->body_chat_references}}}</span>
                        </div>
                        @endif
                        <div class="preview d-flex flex-column gap-1">
                            @if($chat->file_json!=null)
                            @if((in_array(strtolower($chat->extension), $imageExtensions)))
                            <img class="w-50 h-auto rounded-3"
                                src="{{{$chat->file_json==null?'https://randomuser.me/api/portraits/men/12.jpg':asset($chat->file_json)}}}"
                                alt=""
                                onclick="window.open('{{{$chat->file_json==null?null:asset($chat->file_json)}}}')">

                            @else
                            <div class="previewFile d-flex mb-2">
                                <div
                                    class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex align-items-center shadow-sm">
                                    <i class="bi bi-file-earmark text-danger fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-semibold text-primary mb-2">{{{$chat->nama_file}}}</div>
                                        <div class="d-flex gap-2">
                                            <a href="{{{$chat->file_json==null?'https://randomuser.me/api/portraits/men/12.jpg':asset($chat->file_json)}}}"
                                                target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-eye me-1"></i> Preview
                                            </a>
                                            <a href="{{{$chat->file_json==null?'https://randomuser.me/api/portraits/men/12.jpg':asset($chat->file_json)}}}"
                                                download class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-download me-1"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endif
                            @if($chat->contents!=null)

                            <div class="msg">{{{$chat->contents}}}</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
                @else

                @endif
            </div>
            <form class="chat-footer w-100 position-relative" id="chat-footer">
                @if($active==true||count($active)!=0)
                <div id="filePreviewArea" class="mb-2 d-none position-absolute" style="top: -70px;">
                    <div class="d-flex align-items-center gap-2 bg-light border rounded-3 px-3 py-2 position-relative"
                        style="max-width:360px;">
                        <img id="imgPreview" src="" alt="preview"
                            style="max-width:56px;max-height:48px;border-radius:8px;display:none;border:1px solid #e0e0e0;">
                        <div>
                            <span id="filePreview" class="file-preview"></span>
                        </div>
                        <button type="button"
                            class="remove-file btn btn-sm btn-light position-absolute end-0 top-0 mt-1 me-1"
                            id="removeFileBtn" title="Hapus file" style="z-index:2;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="w-100">
                    <!-- Preview area (di atas input) -->
                    <div class="reference_chat d-none align-items-center mb-2" data-references="">
                        <div class="border-start border-4 border-primary ps-2 me-2 small text-secondary"
                            style="min-width: 0;">
                            <span id="referencePreview" class="d-block text-truncate" style="max-width: 250px;">
                                ini apa
                            </span>
                        </div>
                        <button class="btn-close-custom" aria-label="Close" onclick="close_reference_chat(this,event)">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M3 3L13 13M13 3L3 13" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" />
                            </svg>
                        </button>

                    </div>



                    <div class="d-flex align-items-center gap-2">
                        <div class="file-upload-group d-flex align-items-center">
                            <label class="file-upload-label mb-0" title="Attach file">
                                <input type="file" id="fileInput" name="file_input" style="display:none;">
                                <i class="bi bi-paperclip fs-4" style="cursor:pointer;"></i>
                            </label>
                        </div>
                        <input type="text" class="form-control" placeholder="Ketik pesan..." id="chatInput"
                            autocomplete="off" />
                        <button class="btn btn-primary px-4" type="submit" id="sendBtn">Kirim</button>

                        <!-- INPUT FILE -->
                    </div>
                </div>
                @else
                <div class="w-100 d-flex justify-content-center align-items-center">
                    <p>Anda tidak bisa berkomunikasi dengan user ini karena tidak ada lamaran atau pekerjaan yang sedang
                        aktif </p>

                </div>
                @endif
            </form>
            @else
            <div class="no chat w-100 h-100 d-flex justify-content-center align-content-center">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-warning text-black">Tidak ada lamaran yang aktif, jadi anda belum bisa
                        berkomunikasi dengan user ini</span>
                </div>
            </div>
            @endif
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>

        function search(elemen){
            document.querySelectorAll('.person.the_person').forEach(person=>{
                // console.log(person.querySelector('.person_nama').textContent.toLowerCase().includes(elemen.value.toLowerCase()),person.querySelector('.person_nama').textContent);
                if(person.querySelector('.person_nama').textContent.toLowerCase().includes(elemen.value.toLowerCase())){
                    person.style.display = ''
                }
                else{

                    person.style.display = 'none'
                }
            })
        }

        const target = @json($target);
        document.querySelectorAll('.chat-message').forEach(function (item) {
            item.addEventListener('dblclick', function () {
                reply(this);
            });
        });
        let currentReference = null; // Untuk menyimpan referensi pesan yang sedang di-reply

        function reply(elemen) {
            let msgWrap = elemen.closest('.chat-message');
            let msg = msgWrap.querySelector('.msg');

            // Dapatkan isi pesan & nama file jika ada
            let text = msg.innerText.trim();
            let fileName = "";
            let isFile = false;



            const imgInMsg = msg.querySelector('img');
            const fileIcon = msg.querySelector('.bi-paperclip, .bi-image');

            if (fileIcon) {
                let textAfterIcon = fileIcon.parentNode.textContent.trim();
                fileName = textAfterIcon;
                isFile = true;
            }

            // Compose referensi
            let previewText = "";
            if (isFile || imgInMsg) {
                previewText = fileName;
                if (text && text !== fileName) previewText += " - " + text;
            } else {
                previewText = text;
            }

            // Simpan referensi (bisa berupa object kalau mau)
            currentReference = {
                text: text,
                fileName: fileName,
                isFile: !!(isFile || imgInMsg),
                preview: previewText
            };

            // Tampilkan di reference_chat
            let reference = document.querySelector('.reference_chat');
            reference.setAttribute('data-references', msgWrap.getAttribute('data_id_references').toString())
            // console.log('is true: ',)
            // console.log('isi', msgWrap.getAttribute('data_id_references'), msgWrap, 'ini : ' + msgWrap.querySelector('.reply-preview').getAttribute('data-references'))
            let previewElem = document.getElementById('referencePreview');
            previewElem.innerHTML = previewText;
            reference.classList.remove('d-none');
            reference.classList.add('d-flex');
            console.log('isi references: ', reference.getAttribute('data-references'))
        }


        function close_reference_chat(elemen, event) {
            event.preventDefault()
            // event.stopPropagation()
            console.log('masuk referensi')
            console.log(elemen.closest('.reference_chat'))
            elemen.closest('.reference_chat').classList.replace('d-flex', 'd-none')
        }
        // --- File Upload Logic ---
        let selectedFile = null;
        let imageDataUrl = null;

        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('filePreview');
        const filePreviewArea = document.getElementById('filePreviewArea');
        const removeFileBtn = document.getElementById('removeFileBtn');
        const imgPreview = document.getElementById('imgPreview');


        fileInput.addEventListener('change', function (e) {
            // console.log(fileInput.files,fileInput.files.length > 0,fileInput.files && fileInput.files.length > 0)
            if (fileInput.files && fileInput.files.length > 0) {
                console.log('masuk file')
                selectedFile = fileInput.files[0];
                filePreview.textContent = selectedFile.name;
                filePreviewArea.classList.remove('d-none');
                removeFileBtn.classList.remove('d-none');
                console.log(selectedFile.type.startsWith('image/'))
                if (selectedFile.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (evt) {
                        imgPreview.src = evt.target.result;
                        imgPreview.style.display = 'block';
                        imageDataUrl = evt.target.result;
                    }
                    reader.readAsDataURL(selectedFile);
                } else {
                    imgPreview.src = '';
                    imgPreview.style.display = 'none';
                    imageDataUrl = null;
                }
            } else {
                clearFileSelection();
            }
        });

        removeFileBtn.addEventListener('click', function () {
            clearFileSelection();
        });

        function clearFileSelection() {
            selectedFile = null;
            imageDataUrl = null;
            fileInput.value = '';
            filePreview.textContent = '';
            filePreviewArea.classList.add('d-none');
            removeFileBtn.classList.add('d-none');
            imgPreview.src = '';
            imgPreview.style.display = 'none';
        }

        // --- Modify chat submit ---
        document.getElementById('chat-footer').addEventListener('submit', function (e) {
            e.preventDefault();
            const chatInput = document.getElementById('chatInput');
            let text = (chatInput.value.trim() == '') ? null : `<div class="msg">${chatInput.value.trim()}</div>`;

            if (text === "" && !selectedFile) return;
            let reference = null;
            let referensi_chat = cek_references();
            // console.log(referensi_chat)
            if (referensi_chat != null) {
                ;
                reference = `
               <div class="reply-preview" data-references="${cek_references()[1]}">
                            <span class="reply-text">${referensi_chat[0]}</span>
                        </div>
               `;
            }



            let preview = null;

            if (selectedFile) {
                if (selectedFile.type.startsWith('image/') && imageDataUrl) {
                    preview = `
                        <img class="w-50 h-auto rounded-3" src="${imageDataUrl}" alt="">
                `;
                } else {
                    preview = `

                    <div class="d-flex justify-content-end mb-2">
                            <div
                                class="bg-primary bg-opacity-10 rounded-3 p-3 d-inline-flex align-items-center shadow-sm">
                                <i class="bi bi-file-earmark-pdf text-danger fs-3 me-3"></i>
                                <div>
                                    <div class="fw-semibold text-primary mb-2">${selectedFile.name}</div>
                                    <div class="d-flex gap-2">
                                        <a href="${URL.createObjectURL(selectedFile)}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Preview
                                        </a>
                                        <a href="${URL.createObjectURL(selectedFile)}" download
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-download me-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>`

                }
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let file_get = document.querySelector('#fileInput').files[0];

            let formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('receiver', target);
            formData.append('contents', chatInput.value.trim());
            formData.append('file_json', file_get);
            formData.append('chat_reference', referensi_chat == null ? '' : referensi_chat[1]);
            formData.append('body_chat_references', referensi_chat == null ? '' : referensi_chat[0]);
            console.log(formData)
            if (text != null || preview != null) {

                fetch('/make_chat', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    credentials: 'include'
                }).then(response => response.json())
                    .then(data => {
                        console.log('Response dari server:', data);
                        if (data.success == true) {
                            Reset_Input()
                            clearFileSelection();

                            const chatBody = document.querySelector('.chat-body');

                            let referenceHTML = reference ? reference : '';
                            let previewHTML = preview ? preview : '';
                            let textHTML = text ? text : '';

                            chatBody.insertAdjacentHTML('beforeend',
                                `
                                <div class="chat-message user">
                                    <img src="https://randomuser.me/api/portraits/men/50.jpg" class="person-img" />
                                    <div class="chat-inner d-flex flex-column align-items-start">
                                        ${referenceHTML}
                                        <div class="d-flex flex-column gap-1 align-items-end">
                                            ${previewHTML}
                                            ${textHTML}
                                        </div>
                                    </div>
                                </div>
                            `
                            );
                            bubbleMenuBind();
                            newest_chat();

                        }
                        else {
                            fail('Gagal Mengirim', data.success)
                        }
                    })
                    .catch(error => {
                        console.error('ini eror:', error);
                    });
            }
        });

        const sidebar = document.querySelector('.sidebar');
        const backdrop = document.querySelector('.sidebar-backdrop');
        const openSidebarBtn = document.getElementById('openSidebarBtn');

        openSidebarBtn?.addEventListener('click', function () {
            sidebar.classList.add('open');
            backdrop.style.display = 'block';
        });

        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('open');
            backdrop.style.display = 'none';
        });

        document.querySelectorAll('.person').forEach(person => {
            person.addEventListener('click', function () {
                sidebar.classList.remove('open');
                backdrop.style.display = 'none';
            });
        });

        //

        newest_chat();
        function newest_chat() {
            const chatBodies = document.querySelectorAll('.chat-message');
            const newest = chatBodies[chatBodies.length - 1];
            if (newest) {
                console.log('msuk')
                newest.scrollIntoView({ behavior: "smooth" });
            }
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
      <button class="bubble-menu-btn" onclick="reply(this)">Reply</button>
      <button class="bubble-menu-btn" onclick="alert('Fitur edit belum diaktifkan!')">Edit</button>
      <button class="bubble-menu-btn text-danger" onclick="bubbleDeleteMsg(this)">Delete</button>
    `;
            msgElem.parentElement.appendChild(bubble);
            setTimeout(function () {
                window.addEventListener('click', removeBubbleMenu, { once: true });
            }, 50);
        }
        function removeBubbleMenu() {
            document.querySelectorAll('.bubble-menu').forEach(function (el) { el.remove(); });
        }

        function cek_references() {
            let reference = document.querySelector('.reference_chat')
            if (reference.classList.contains('d-flex')) {
                return [reference.querySelector('#referencePreview').textContent, reference.getAttribute('data-references')];
            }
            else {
                return null;
            }
        }

        function clear_reference_chat() {
            let reference = document.querySelector('.reference_chat')
            reference.classList.replace('d-flex', 'd-none')
        }

        function Reset_Input() {
            document.querySelector('.reference_chat').classList.replace("d-flex", "d-none")

            let reset = [
                document.querySelector('#fileInput'),
                document.querySelector('#chatInput'),
            ]
            reset.forEach(e => {
                e.value = ""
            })
        }
        function bubbleDeleteMsg(btn) {
            const msgWrap = btn.closest('.chat-message');
            msgWrap.remove();
            removeBubbleMenu();
        }
        bubbleMenuBind();

        function refresh_chat() {

        }
    </script>
</body>

</html>
