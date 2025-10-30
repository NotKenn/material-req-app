<?php

namespace App\Observers;
use App\Models\penerima;
use App\Models\matRequest;

class penerimaObserver
{
    public function created(matRequest $mr)
    {
        if($mr->penerima_id) {
            return;
        }

        $requester = $mr->requester;
        if(!$requester) {
            return;
        }

        $penerima = penerima::firstOrCreate(
            [
            'namaPenerima'      => $requester->namaKontakPT,
            'lokasiPengantaran' => $requester->alamatPT,
            'nomorKontak'       => $requester->noTelpKontakPT,
            ]
        );

        $mr->penerima_id = $penerima->id;
        $mr->saveQuietly();

    }
}
