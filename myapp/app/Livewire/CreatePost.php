<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title("記事作成ページ")]
class CreatePost extends Component
{
    use WithFileUploads;

    #[Validate("required", message: "タイトルは必須です")]
    #[Validate("min:3", message: "タイトルは3文字以上で入力してください")]
    public $title = "";

    #[Validate("required", message: "本文は必須です")]
    public $body = "";

    #[Validate("nullable|image|max:2048", message: "画像は2MB以下の画像ファイルを選んでください")]
    public $image;

    public function save() {
        $this->validate();

        Post::create([
            'title' => $this->title,
            'body' => $this->body,
            'user_id' => Auth::id(),
            'image_path' => $this->image?->store('posts'),
        ]);

        $this->reset(['title', 'body', 'image']);
        session()->flash('status', '記事を投稿しました！');

        // return $this->redirect('/posts', navigate: true);
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
