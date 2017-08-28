<table class="table table-condensed table-hover">
    <thead>
        <tr>
            
            <th>Title</th>
            <th>Likes</th>
            <th>Posted On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <!-- BEGIN gag_list -->
        <tr>
            <td><a href="{SITE_URL}/gag/show/id/{GAG_ID}"><h3>{GAG_TITLE}</h3></a></td>
            <td><span class="badge">{GAG_LIKES}</span></td>
            <td>{GAG_DATE}</td>
            <td><a href="{SITE_URL}/gag/edit-gag/id/{GAG_ID}" class="btn btn-primary">Edit</a>
                <a href="{SITE_URL}/gag/delete-gag/id/{GAG_ID}" class="btn btn-danger">Delete</a>
            </td>
        </tr> 
    <!-- END gag_list -->    
    </tbody>
</table>