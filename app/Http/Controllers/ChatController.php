<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\chat;
use App\Models\Pekerjaan;
use App\Models\Pelamar;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function update_lamaran($idLamaran, $sender, $receiver)
    {
        $Lamaran = Pelamar::where('id', $idLamaran)->first();
        $job = Pekerjaan::where('id', $Lamaran->job_id)->first();
        $chat = new chat();
        $chat->sender = $sender;
        $chat->receiver = $receiver;
        $chat->pekerjaan_id = $job->id;
        $chat->Lamaran_status = $Lamaran->status;
        return $chat->save();
    }

    public function delete_lamaran($idLamaran, $sender, $receiver)
    {
        $Lamaran = Pelamar::where('id', $idLamaran)->first();
        $job = Pekerjaan::where('id', $Lamaran->job_id)->first();
        $chat = new chat();
        $chat->sender = $sender;
        $chat->receiver = $receiver;
        $chat->pekerjaan_id = $job->id;
        $chat->Lamaran_status = 'Lamaran Dihapus';
        return $chat->save();
    }





    public function Lamaran($idPekerjaan)
    {
        $find_lamaran = Pelamar::where('job_id', $idPekerjaan)
            ->where('user_id', session('account')['id'])->first();
        $id_Target = Pekerjaan::where('id', $find_lamaran->job_id);
        if (!in_array($find_lamaran->status, ['Gagal', 'selesai', 'ditolak'])) {
            return redirect('/Chat/' . $id_Target->pembuat);
        } else {
            return redirect('/Chat/' . $id_Target->pembuat)->with('fail', ['Chat Terkait Pekerjaan ini tidak ada!', 'Pekerjaan sudah selesai atau lamaran anda gagal!']);
        }
    }

    public function index($id_target)
    {
        $session_account_id = session('account')['id'];
        $user = Users::where('id', $id_target)->first();
        $subQuery = DB::table('chats')
            ->select('sender as user_id')
            ->where(function ($q) use ($session_account_id) {
                $q->where('receiver', $session_account_id)
                    ->orWhere('sender', $session_account_id);
            })
            ->union(
                DB::table('chats')
                    ->select('receiver as user_id')
                    ->where(function ($q) use ($session_account_id) {
                        $q->where('receiver', $session_account_id)
                            ->orWhere('sender', $session_account_id);
                    })
            );
        $results =  "
            SELECT
                t.counterpart_id,
                c.*,
                d.*,
                d.nama as nama_user
            FROM (
                SELECT
                    CASE
                        WHEN sender = ? THEN receiver
                        ELSE sender
                    END AS counterpart_id,
                    MAX(id) AS last_chat_id
                FROM chats
                WHERE sender = ? OR receiver = ?
                GROUP BY counterpart_id
            ) AS t
            JOIN chats c ON c.id = t.last_chat_id
            join users d on d.id=t.counterpart_id
            ";

        $chats = DB::select($results, [$session_account_id, $session_account_id, $session_account_id]);



        // dd($results);

        // Query utama: join subquery ke users
        $to_text = DB::query()
            ->fromSub($subQuery, 'a')
            ->join('users as b', 'a.user_id', '=', 'b.id')
            ->select('a.*', 'b.*')
            ->get();
        // dd($to_text);
        // dd(session('account')['id']);
        $all_chats = null;
        if (session('account')['role'] != 'admin') {
            $all_chats = DB::table('chats as c')
                ->selectRaw('
                c.id AS id_chat,
                c.*,
                a.*,
                d.contents AS content_references,
                c.created_at AS sent
            ')
                ->leftJoin('pekerjaans as a', 'a.id', '=', 'c.pekerjaan_id')
                ->leftJoin('chats as d', 'd.chat_references', '=', 'c.id')
                ->where(function ($query) use ($session_account_id, $id_target) {
                    $query->where(function ($q) use ($session_account_id, $id_target) {
                        $q->where('c.sender', $session_account_id)
                            ->where('c.receiver', $id_target);
                    })->orWhere(function ($q) use ($session_account_id, $id_target) {
                        $q->where('c.receiver', $session_account_id)
                            ->where('c.sender', $id_target);
                    });
                })
                ->get();
        } else {
            $all_chats = DB::table('chats as c')
                ->selectRaw('
                c.id AS id_chat,
                c.*,
                a.*,
                d.contents AS content_references,
                c.created_at AS sent
            ')
                ->leftJoin('pekerjaans as a', 'a.id', '=', 'c.pekerjaan_id')
                ->leftJoin('chats as d', 'd.chat_references', '=', 'c.id')
                ->where(function ($query) use ($session_account_id, $id_target) {
                    $query->where(function ($q) use ($session_account_id, $id_target) {
                        $q->where('c.sender', $session_account_id)
                            ->where('c.receiver', $id_target);
                    })->orWhere(function ($q) use ($session_account_id, $id_target) {
                        $q->where('c.receiver', $session_account_id)
                            ->where('c.sender', $id_target);
                    });
                })
                ->get();

            if (count($all_chats) < 1) {
                $all_chats = [true];
            }
        }

        $active = null;
        if (session('account')['role'] == 'mitra') {
            $active = DB::table('pelamars as a')
                ->join('pekerjaans as b', 'a.job_id', '=', 'b.id')
                ->select('a.*', 'b.pembuat')
                ->where('a.user_id', $id_target)
                ->where('b.pembuat', $session_account_id)
                ->whereNotIn('a.status', ['ditolak', 'Gagal', 'Lamaran Dihapus', 'Selesai'])
                ->whereNot('is_delete', true)
                ->get();
        } elseif (session('account')['role'] == 'user') {
            $active = DB::table('pelamars as a')
                ->join('pekerjaans as b', 'a.job_id', '=', 'b.id')
                ->select('a.*', 'b.pembuat')
                ->where('a.user_id', $session_account_id)
                ->where('b.pembuat', $id_target)
                ->whereNotIn('a.status', ['ditolak', 'Gagal', 'Lamaran Dihapus', 'Selesai'])
                ->whereNot('is_delete', true)
                ->get();
        } else {
            $active = true;
        }

        $all_users = null;
        if(session('account')['role'] == 'admin') {
            $all_users = Users::all();
        }
        $target = $id_target;
        $all = $chats;
        // dd($active, $all_chats);
        // dd('id gw: '.session('account')['id'],'id lawan: '.$id_target,$all_chats);
        // dd($all);


        return view('Dewa.need_auth.chat', compact('to_text', 'all_chats', 'target', 'all', 'user', 'active','all_users'));
    }

    public function store(Request $request)
    {
        // dd($request);
        // return response()->json([
        //     'success' => true,
        //     'data' => $request->all(),
        // ]);
        // return response()->json([
        //             'success' => 'masuk',
        //         ]);
        // return response()->json($request->file('file_json'));
        $chat = new chat();
        $chat->sender = session('account')['id'];
        $chat->receiver = $request->receiver;
        // return response()->json([
        //     'success' => true,
        //     'data' => [$request->has('contents'),$request->input('contents'),$request->file('file_json')]
        // ]);
        try {
            if (($request->contents != null) || ($request->file('file_json') != null)) {
                $chat->contents = $request->has('contents') ? $request->input('contents') : null;
                $chat->file_json = null;

                if ($request->hasFile('file_json')) {
                    $file = $request->file('file_json');
                    $filename = session('account')['id'] . $file->getClientOriginalName();
                    $filePath = $file->storeAs('chat_file', $filename, 'public');
                    $chat->file_json = 'storage/' . $filePath;
                    $chat->extension = $file->getClientOriginalExtension();
                    $chat->nama_file = $file->getClientOriginalName();
                }

                $chat->chat_references = null;
                $chat->body_chat_references = null;
                if ($request->chat_reference != null) {
                    $chat->chat_references = $request->chat_reference;
                    $chat->body_chat_references = $request->body_chat_references;
                }

                if ($chat->save()) {
                    return response()->json([
                        'success' => true,
                        'data' => $chat
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'data' => "Terjadi kesalahan dalam menyimpan pesan"
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'data' => "Tidak ada pesan yang perlu dikirim"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => $e->getMessage()
            ]);
        }
    }
}
