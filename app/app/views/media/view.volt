<div content="Content-Type: {{ type }}">
{% if kind == 'video' %}
    <video id="videoJsPlayer"
           class="video-js"
           controls
           autoplay
           preload="auto"
           poster="//vjs.zencdn.net/v/oceans.png"
           data-setup='{}'>
        <source src="{{ src }}" type="{{ type }}"/>
        <p class="vjs-no-js">
            To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
        </p>
    </video>
{% else %}
    <img src="{{ src }}" alt="{{ title }}" title="{{ title }}" />
{% endif %}
</div>