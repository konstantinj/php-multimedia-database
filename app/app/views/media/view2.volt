<div content="Content-Type: {{ type }}">
    <video width="{{ width }}" height="{{ height }}" controls autoplay preload="metadata">
        <source src="data:{{ type }};base64,{{ data }}"/>;
    </video>
</div>