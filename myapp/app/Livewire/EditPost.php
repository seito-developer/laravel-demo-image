<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;

    #[Validate('required|min:3')]
    public $title = '';

    #[Validate('required')]
    public $body = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    public function mount(Post $post) {
        if($post->user_id !== Auth::id()){
            abort(403);
        }

        $this->post = $post;
        $this->title = $post->title;
        $this->body = $post->body;
    }

    public function update(){
        $this->validate();

        if($this->post->user_id !== Auth::id()){
            abort(403);
        }

        $data = [
            'title' => $this->title,
            'body' => $this->body,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('posts', 'r2');
        }

        $this->post->update($data);

        session()->flash('status', '記事を更新しました！');

        return $this->redirect('/my-posts', navigate: true);
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
