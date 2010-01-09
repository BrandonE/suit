<?xml version="1.0" encoding="utf-8" ?>
<import>
    [loop vars="[var json=\"true\"]loop=>directories[/var]"]
    <directory>
        <title>[loopvar]titletoken[/loopvar]</title>
        [loop vars="[loopvar json=\"true\"]array[/loopvar]"]
        <sub>[loopvar]arraytoken[/loopvar]</sub>
        [/loop]
    </directory>
    [/loop]
    [loop vars="[var json=\"true\"]loop=>files[/var]"]
    <file>
        <title>[loopvar]titletoken[/loopvar]</title>
        [loop vars="[loopvar json=\"true\"]array[/loopvar]"]
        <sub>[loopvar]arraytoken[/loopvar]</sub>
        [/loop]
        <template>[loopvar]templatetoken[/loopvar]</template>
    </file>
    [/loop]
</import>