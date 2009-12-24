<?xml version="1.0" encoding="utf-8" ?>
[loop vars="[var serialize=\"true\"]loop=>directories[/var]"]
<directory>
    <title>[loopvar]titletoken[/loopvar]</title>
    [loop vars="[loopvar serialize=\"true\"]array[/loopvar]"]
    <array>[loopvar]arraytoken[/loopvar]</array>
    [/loop]
</directory>
[/loop]
[loop vars="[var serialize=\"true\"]loop=>files[/var]"]
<file>
    <title>[loopvar]titletoken[/loopvar]</title>
    [loop vars="[loopvar serialize=\"true\"]array[/loopvar]"]
    <array>[loopvar]arraytoken[/loopvar]</array>
    [/loop]
    <template>[loopvar]templatetoken[/loopvar]</template>
</file>
[/loop]