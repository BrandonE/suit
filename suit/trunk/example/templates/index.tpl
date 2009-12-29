[trim]
[template]header=>header=>parse[/template]
    <div class="section">
        <h2>[var]language=>example[/var]</h2>
        <p>Welcome to the SUIT example page. SUIT uses rules, or nodes, to transform its templates. The code defines these nodes and passes them to a parse function, which does the transformation. This means that by using SUIT, you can create any node imaginable in a few simple steps. However, most can benefit from the example nodes packaged with SUIT. Here, you will learn how to set up each node and what each of them do.</p>
        <ul>
            <li><a href="#basic">Basic Node</a></li>
            <li><a href="#attributes">Attributes</a></li>
            <li><a href="#templates">Templates</a></li>
        </ul>
        <h3 id="basic">Basic Node</h3>
        <p>To understand all of the default nodes, you must understand the basic premise of creating one. All of the nodes can be seen setup in config.php. Here's a look at a simple node array:</p>
        <fieldset>
            <legend>Example Nodes</legend>
<?php
$suit->vars['nodes'] = array
(
    '[node]' => array
    (
        'close' => '[/node]',
        'function' => array
        (
            array
            (
                'function' => 'node'
            )
        ),
        'var' => array
        (
            'exception' => 'Test'
        )
    )
);
?>
        </fieldset>
        <p>The key represents the opening string. When parsing the document, SUIT will open a tag when it hits \[node], close when it hits \[/node], and will parse whatever lies in between, or the case. The node knows what to do with case because provide 'function' and 'var'. The 'function' array contains functions to run on the case and the class it can be located on. The case will be modified in some way by the function provided. 'var' contains any special information we want to pass to the function. This allows us to create multiple nodes for every function. In general, nodes make 'var' an array to send multiple pieces of data.</p>
        <p>To get a better idea of how this works, we define node to the following:
        <fieldset>
            <legend>function node</legend>
<?php
function node($params)
{
    if ($params['case'] == $params['var']['exception'])
    {
        $params['case'] = strtolower($params['case']);
    }
    else
    {
        $params['case'] = strtoupper($params['case']);
    }
    return $params;
}
?>
        </fieldset>
        <p>We parse the following template:</p>
        <fieldset>
            <legend>Template</legend>
            \[node]example\[/node] \[node]aNoThEr\[/node] \[node]Test\[/node]
        </fieldset>
        <p>The result would be:</p>
        <fieldset>
            <legend>Result</legend>
            [node]example[/node] [node]aNoThEr[/node] [node]Test[/node]
        </fieldset>
        <p>The function says that every case except for the defined exception should be converted to uppercase, while the exception should be converted to lowercase. As we defined the exception as 'Test', SUIT made everything but Test uppercase and Test lowercase.</p>
        <h3 id="attributes">Attributes</h3>
        <p>Many nodes need to be modified for certain cases in the template. The above example with the static 'var' simply doesn't provide much flexibility. For this very reason, attributes have been created. Attributes create instances of a node with its 'var' defined differently. Let's take the previous example and add an attribute node:</p>
        <fieldset>
            <legend>Example Nodes</legend>
<?php
$suit->vars['nodes'] = array
(
    '[node]' => array
    (
        'close' => '[/node]',
        'function' => array
        (
            array
            (
                'function' => 'node'
            )
        ),
        'var' => array
        (
            'exception' => 'Test'
        )
    ),
    '[node' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[node]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'quote' => '"'
        )
    )
);
?>
        </fieldset>
        <p>This new node has similar attributes, with the addition of some new ones. 'attribute' shows what node it should be modelled after. Thus, this node attempts to create an instance of \[node] with different variables. 'skip' means that this case should not be parsed recursively. 'var' contains two keys, what the equal symbol should be, and what the quote symbol should be. These may be standard, but they are put in 'var' in case anyone wants to use single quotes, etc. The opening string looks the same as the first node, except for the closing bracket. The closing bracket has been moved to the closing string. So, just like the original node matches everything in between \[node] and \[/node], this one matches everything in between \[node and \], allowing us to put attributes in between. Using our previous example:</p>
        <p>We parse the following template:</p>
        <fieldset>
            <legend>Template</legend>
            \[node]example\[/node] \[node exception="aNoThEr"\]aNoThEr\[/node] \[node]Test\[/node] \[node exception="false"\]Test\[/node]
        </fieldset>
        <p>The result would be:</p>
        <fieldset>
            <legend>Result</legend>
            [node]example[/node] [node exception="aNoThEr"]aNoThEr[/node] [node]Test[/node] [node exception="false"]Test[/node]
        </fieldset>
        <p>The attribute modified the second case so that aNoThEr should be lowercased. At the same time, in the fourth example, the attribute removed the exception, so SUIT made Test uppercase. As the other cases have no attributes, and we defined the default exception to be 'Test', they function as normal.</p>
        <h3 id="templates">Templates</h3>
        <p>As the above examples have little to no point, let's take a look at nodes that do. What would be the point of a templating system if we couldn't grab templates inside of our templates?</p>
        <fieldset>
            <legend>Example Nodes</legend>
<?php
$suit->vars['files'] = array
(
    'code' => 'code',
    'templates' => 'templates'
);
$suit->vars['filetypes'] = array
(
    'code' => 'inc.php',
    'templates' => 'tpl'
);
$suit->vars['nodes'] = array
(
    '[template]' => array
    (
        'close' => '[/template]',
        'function' => array
        (
            array
            (
                'function' => 'templates',
                'class' => $nodes
            )
        ),
        'var' => array
        (
            'files' => $suit->vars['files'],
            'filetypes' => $suit->vars['filetypes'],
            'delimiter' => '=>'
        )
    ),
    '[template' => array
    (
        'close' => ']',
        'function' => array
        (
            array
            (
                'function' => 'attribute',
                'class' => $nodes
            )
        ),
        'attribute' => '[template]',
        'skip' => true,
        'var' => array
        (
            'equal' => '=',
            'list' => array('label'),
            'quote' => '"'
        )
    )
);
?>
        </fieldset>
        <p>This node matches everything in between \[template] and \[/template], and runs gettemplate using the case as instructions. Let's take the following example:</p>
        <fieldset>
            <legend>Template</legend>
            \[template]menu=>parse\[/template]
            <br />Test
        </fieldset>
        <p>The result would be:</p>
        <fieldset>
            [template]menu=>parse[/template]
            <br />Test
        </fieldset>
        <p>The template node grabbed templates/menu.tpl and ran code/parse.inc.php on it which parsed all of its nodes.</p>
        <p>'files' shows the directories to restrict to for both templates and code. 'filetypes' shows the filetypes to restrict to for both templates and code. 'separator' shows what symbol separates the elements. You might be thinking that with the attribute node, all of these values can be modified. To prevent them from being modified, we defined 'list' in the attribute to say that we can only define 'label'. When we define label as an attribute, this gettemplate call will be logged in suit->debug. No other attribute can be defined.</p>
    </div>
[template]footer=>parse[/template]
[/trim]