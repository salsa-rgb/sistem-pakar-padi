<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Symptom;
use App\Models\Disease;

class ChatPadi extends Component
{
    public $messages = []; // Menyimpan balon chat
    public $currentStep = 0;
    public $selectedSymptoms = [];

    public function mount()
    {
        // Pesan pembuka saat pertama kali dibuka
        $this->messages[] = [
            'role' => 'bot',
            'text' => 'Halo! Saya asisten pakar padi. Mari kita diagnosa tanaman Anda. Apakah Anda melihat gejala tertentu?'
        ];
    }

    public function render()
    {
        return view('livewire.chat-padi');
    }
}