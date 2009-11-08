[else version]
[!tie/header!]
    <div class="section">
        <h2>[:section:]</h2>
        [if dashboard]
        <table class="dashboard">
            <tr>
                <th>[:language=>dashboardinfo:]</th>
            </tr>
            <tr>
                <td class="category">[:language=>currentsuitversion:]</td>
                <td>[:currentsuitversion:]</td>
                <td class="category">[:language=>latestsuitversion:]</td>
                <td>
                    <iframe src="[:path=>url:][:path=>urlquerychar:]suitversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[:language=>na:]</strong></noframes>
                </td>
                <td class="category">[:language=>currenttieversion:]</td>
                <td>[:currenttieversion:]</td>
                <td class="category">[:language=>latesttieversion:]</td>
                <td>
                    <iframe src="[:path=>url:][:path=>urlquerychar:]tieversion=true" height="17" width="28" scrolling="no" frameborder="0"></iframe>
                    <noframes><strong style="color: red;">[:language=>na:]</strong></noframes>
                </td>
            </tr>
            <tr>
                <td class="category">[:language=>servertype:]</td>
                <td>[:servertype:]</td>
                <td class="category">[:language=>phpversion:]</td>
                <td>[:phpversion:]</td>
                <td class="category">[:language=>fileuploads:]</td>
                <td>[if fileuploads][:language=>on:][/if fileuploads][else fileuploads][:language=>off:][/else fileuploads]</td>
                <td class="category">[:language=>registerglobals:]</td>
                <td>[if registerglobals]<strong style="color: red;">[:language=>on:]</strong>[/if registerglobals][else registerglobals][:language=>off:][/else registerglobals]</td>
            </tr>
            <tr>
                <td class="category">[:language=>magicquotesgpc:]</td>
                <td>[if magicquotesgpc]<strong style="color: red;">[:language=>on:]</strong>[/if magicquotesgpc][else magicquotesgpc][:language=>off:][/else magicquotesgpc]</td>
                <td class="category">[:language=>magicquotesruntime:]</td>
                <td>[if magicquotesruntime]<strong style="color: red;">[:language=>on:]</strong>[/if magicquotesruntime][else magicquotesruntime][:language=>off:][/else magicquotesruntime]</td>
                <td class="category">[:language=>magicquotessybase:]</td>
                <td>[if magicquotessybase]<strong style="color: red;">[:language=>on:]</strong>[/if magicquotessybase][else magicquotessybase][:language=>off:][/else magicquotessybase]</td>
                <td class="category">[:language=>phpinfo:]</td>
                <td><a href="[:path=>url:][:path=>urlquerychar:]section=phpinfo">[:language=>link:]</a></td>
            </tr>
        </table>
        [/if dashboard]
        [else dashboard]
[:tie:]
        [/else dashboard]
    </div>
[!tie/footer!]
[section separator] - [/section separator]
[/else version]
[if version]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>[:language=>title:]</title>
</head>

<body style="background: #FFF; margin: 0 0 10px; padding:0; font-family: 'Trebuchet MS', Verdana, Tahoma, Arial, Sans-serif; font-size: 12px; color: #4B4B4B">
[if currentversion]<strong style="color: red;">[/if currentversion][:version:][if currentversion]</strong>[/if currentversion]
</body>
</html>
[/if version]