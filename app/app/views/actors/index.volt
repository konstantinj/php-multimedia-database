<h1>Actor Management</h1>

{{ form('actors/save') }}
    {% for element in form %}
        {% if is_a(element, 'Phalcon\Forms\Element\Hidden') %}
            {{ element }}
        {% else %}
            <div class="form-group">
                {{ element.label(['class': 'control-label']) }}
                <div class="controls">
                    {{ element.render(['class': 'form-control']) }}
                </div>
            </div>
        {% endif %}
    {% endfor %}
    <div class="form-group" style="margin:15px 0;">
        {{ submit_button('Save', 'class': 'btn btn-primary') }}
    </div>
</form>

{% for item in actors %}
{% if loop.first %}
<table id="actors" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
{% endif %}
        <tr>
            <td>{{ item.id }}</td>
            <td>{{ item.name }}</td>
            <td>{% if aclHelper.isAllowed('actors', 'save') %}{{ link_to("actors/index/" ~ item.id, '<i class="fa fa-edit"></i>', "class": "btn btn-default", "title": "Edit") }}{% endif %}</td>
            <td>{% if aclHelper.isAllowed('actors', 'delete') %}{{ link_to("actors/delete/" ~ item.id, '<i class="fa fa-remove"></i>', "class": "btn btn-default", "title": "Delete") }}{% endif %}</td>
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
    $('#actors').DataTable();
} );
</script>