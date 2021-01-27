@component('mail::message')
# Your Post was Liked

{{ $liker->name }} Liked one of your Posts

@component('mail::button', ['url' => route('posts',$post)])
View Post
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
