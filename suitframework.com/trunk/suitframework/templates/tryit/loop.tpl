<table width="100%" border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Group</th>
        </tr>
    </thead>
    <tbody>
        [loop value="value" list="[var json='true']members[/var]"]
        <tr>
            <td>[var]value.name[/var]</td>
            <td>[var]value.group[/var]</td>
        </tr>
        [/loop]
    </tbody>
</table>