<h1>Media Management</h1>

{{ form('media/save', 'enctype': 'multipart/form-data') }}
    {% for element in form %}
        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
            {{ element }}
        {% else %}
            {% if is_a(element, 'Phalcon\Forms\Element\File') %}
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Media item</span>
                    </div>
                    <div class="custom-file">
                        {{ element.render(['class': 'form-control-file']) }}
                        {{ element.label(['class': 'custom-file-label']) }}
                    </div>
                </div>
                <script>
                    $('#data').on('change',function(){
                        var fileName = $(this).val();
                        $(this).next('.custom-file-label').html(fileName);
                    })
                </script>
            {% else %}
                <div class="form-group">
                    {{ element.label(['class': 'control-label']) }}
                    <div class="controls">
                        {{ element.render(['class': 'form-control']) }}
                    </div>
                </div>
            {% endif %}
        {% endif %}
    {% endfor %}
    <div class="form-group" style="margin:15px 0;">
        {{ submit_button('Save', 'class': 'btn btn-primary') }}
    </div>
</form>

{% for item in media %}
{% if loop.first %}
<table id="media" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Duration</th>
            <th>Type</th>
            <th>Width</th>
            <th>Height</th>
            <th>Actors</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
{% endif %}
        <tr>
            <td>{{ item.id }}</td>
            <td>{{ item.title }}</td>
            <td>{{ item.duration }}</td>
            <td>{{ item.type }}</td>
            <td>{{ item.width }}</td>
            <td>{{ item.height }}</td>
            <td>
            {% for actor in item.actors %}
            {{ link_to("actors/index/" ~ actor.id, actor.name, "title": "Edit Actor") }}{% if !loop.last %}, {% endif %}
            {% endfor %}
            </td>
            <td>{% if aclHelper.isAllowed('media', 'save') %}{{ link_to("media/index/" ~ item.id, '<i class="fa fa-edit"></i>', "class": "btn btn-default", "title": "Edit") }}{% endif %}</td>
            <td>{% if aclHelper.isAllowed('media', 'delete') %}{{ link_to("media/delete/" ~ item.id, '<i class="fa fa-remove"></i>', "class": "btn btn-default", "title": "Delete") }}{% endif %}</td>
            <td>{% if aclHelper.isAllowed('media', 'view') %}{{ link_to("media/view/" ~ item.id, '<i class="fa fa-play-circle"></i>', "class": "btn btn-default", "title": "View") }}{% endif %}</td>
            <td>{% if aclHelper.isAllowed('media', 'xml') %}{{ link_to("media/xml/" ~ item.id, '<i class="fa fa-code"></i>', "class": "btn btn-default", "title": "XML") }}{% endif %}</td>
        </tr>
{% if loop.last %}
    </tbody>
</table>
{% endif %}
{% else %}
    No media found
{% endfor %}

<script type="text/javascript">
$(document).ready(function() {
    $('#media').DataTable();
} );
</script>