<!DOCTYPE html>
<html>
<head>
    <title>Live SQL Parser</title>
    <link rel="stylesheet" href="assets/nice_r.css"/>
    <script src="assets/nice_r.js"></script>
    <link rel="stylesheet" href="vendor/codemirror/lib/codemirror.css"/>
    <script src="vendor/codemirror/lib/codemirror.js"></script>
    <script src="vendor/codemirror/mode/sql/sql.js"></script>
    <script src="vendor/codemirror/addon/runmode/runmode.js"></script>
    <script src="vendor/codemirror/mode/clike/clike.js"></script>
    <script src="vendor/codemirror/mode/php/php.js"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            position: relative;
        }

        pre {
            margin: 0;
        }

        #left, #right {
            top: 0;
            bottom: 0;
            width: 50%;
            position: absolute;
            overflow: auto;
            box-sizing: border-box;
            border: 8px solid #F6F6F6;
        }

        #left {
            left: 0;
            border-right-width: 4px;
        }

        #right {
            right: 0;
            border-left-width: 4px;
        }

        #output_dump, #output_nice {
            left: 0;
            right: 0;
            height: 50%;
            position: absolute;
            overflow: auto;
            box-sizing: border-box;
        }

        #output_dump {
            top: 0;
            border-bottom: 4px solid #F6F6F6;
        }

        #output_nice {
            bottom: 0;
            border-top: 4px solid #F6F6F6;
        }

        .CodeMirror {
            height: auto;
        }
    </style>
</head><body>
<div id="left">
    <textarea id="code"><?php echo htmlspecialchars($sql, ENT_QUOTES); ?></textarea>
</div>
<div id="right">
    <div style="position: relative; height: 100%;">
        <pre id="output_dump" class="cm-s-default"></pre>
        <div id="output_nice"></div>
    </div>
</div>
<script>
    window.onload = function () {
        var highlight = function(content){
            CodeMirror.runMode(content.dump, "text/x-php", document.getElementById("output_dump"));
            document.getElementById("output_nice").innerHTML = content.nice;
        };

        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            mode: "text/x-mysql",
            tabMode: "indent",
            matchBrackets: true,
            lineNumbers: true,
            viewportMargin: Infinity
        });

        editor.on("changes", function(cm){
            var f = new FormData();
            f.set('parse', '1');
            f.set('code', cm.getValue());
            
            var r = new XMLHttpRequest();
            r.open("POST", "/", true);
            r.onreadystatechange = function () {
                if (r.readyState != 4 || r.status != 200) return;
                highlight(JSON.parse(r.responseText));
            };
            r.send(f);
        });

        document.getElementById("left").onclick = function(){ editor.focus(); };

        highlight(<?php echo get_output($sql); ?>);
    };
</script>
</body>
</html>