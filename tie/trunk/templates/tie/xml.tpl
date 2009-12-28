<?xml version="1.0" encoding="utf-8" ?>
<import>
    [loop vars="[var serialize=\"true\"]loop=>directories[/var]"]
    <directory>
        <title>[loopvar]titletoken[/loopvar]</title>
        [loop vars="[loopvar serialize=\"true\"]array[/loopvar]"]
        <sub>[loopvar]arraytoken[/loopvar]</sub>
        [/loop]
    </directory>
    [/loop]
    [loop vars="[var serialize=\"true\"]loop=>files[/var]"]
    <file>
        <title>[loopvar]titletoken[/loopvar]</title>
        [loop vars="[loopvar serialize=\"true\"]array[/loopvar]"]
        <sub>[loopvar]arraytoken[/loopvar]</sub>
        [/loop]
        <template>[loopvar]templatetoken[/loopvar]</template>
    </file>
    [/loop]
</import>