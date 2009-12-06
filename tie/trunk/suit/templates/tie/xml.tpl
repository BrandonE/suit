<?xml version="1.0" encoding="utf-8" ?>
[loop vars="[:loop=>directories:]"]
<directory>
    <title>[|titletoken|]</title>
    [loop vars="[|array|]"]
    <array>[|arraytoken|]</array>
    [/loop]
</directory>
[/loop]
[loop vars="[:loop=>files:]"]
<file>
    <title>[|titletoken|]</title>
    [loop vars="[|array|]"]
    <array>[|arraytoken|]</array>
    [/loop]
    <template>[|templatetoken|]</template>
</file>
[/loop]