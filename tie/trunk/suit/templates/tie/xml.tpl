<?xml version="1.0" encoding="utf-8" ?>
[loop vars="[var]loop=>directories[/var]"]
<directory>
    <title>[loopvar]titletoken[/loopvar]</title>
    [loop vars="[loopvar]array[/loopvar]"]
    <array>[loopvar]arraytoken[/loopvar]</array>
    [/loop]
</directory>
[/loop]
[loop vars="[var]loop=>files[/var]"]
<file>
    <title>[loopvar]titletoken[/loopvar]</title>
    [loop vars="[loopvar]array[/loopvar]"]
    <array>[loopvar]arraytoken[/loopvar]</array>
    [/loop]
    <template>[loopvar]templatetoken[/loopvar]</template>
</file>
[/loop]