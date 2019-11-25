<?php
    $handle=htmlspecialchars($_GET["id"]);
    $url = "https://repositorio.ufsc.br/handle/".$handle."?show=full";
    $data = array
    (
        "author"=>"DC.creator",
        "published"=>"DCTERMS.issued",
        "lang"=>"DC.language",
        "subject"=>"DC.subject",
        "title"=>"DC.title",
        "type"=>"DC.type",
        "link" =>"citation_pdf_url",
        "abstract"=>"DCTERMS.abstract",
        "publisher"=>"publisher",
        "edition"=>""
    );
    include_once('dom/simple_html_dom.php');
    ## write your simple cURL function
    function useCurl($url){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)');
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);curl_close($ch);
            return $output;
            unset($output);
          }
     ## define your url variable
     ## call the function above, and asigned the output to variable html_file
     $html_file = useCurl($url);
     ## use the str_get_html method from the simple html dom
     $html = str_get_html($html_file);
     ## iterate through the parse data --> meta with the content attribute
     $numbers = array_keys($data);
     for($x=0;$x<count($data);$x++){
        foreach($html->find('meta[name='.$data[$numbers[$x]].']') as $element)
        {
            $data[$numbers[$x]]=$element->content;
        }
     }
     $data['abstract']=html_entity_decode($data['abstract'], ENT_XML1);
     //publisher
     $start = strpos($data['abstract'],'Editoria:')+10;
     $end = strpos($data['abstract'],'. ',$start);
     $data['publisher']=substr($data['abstract'],$start,($end-6)-$start);
     if(strpos($data['abstract'],'Edição:')!==false){
        $start = strpos($data['abstract'],'Edição:')+10;
        $end = strpos($data['abstract'],' Editoria:');
        $data['edition']=substr($data['abstract'],$start,$end-$start);
     }
     $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 
    $currenturl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
    <head>
        <script>
                    var url_string = window.location.href;
                    var url = new URL(url_string);
                    var c = url.searchParams.get("id");
                    if(c=="getout"){
                        window.close('','_parent','');
                    }
        </script>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title><?php echo $data["title"];?></title>
        <meta property="og:title" content="<?php echo $data["title"];?>" />
        <meta property="og:type" content="<?php echo $data["type"];?>" />
        <meta property="og:url" content="<?php echo $currenturl;?>" />
        <meta property="og:image" content="<?php echo $protocol . $_SERVER['HTTP_HOST'];?>/wavesviewer/resources/ogimage.gif" />
        <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
        <link rel="manifest" href="favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-blue.min.css" />
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link rel="stylesheet" href="dist/needsharebutton.min.css">       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="jquery.msgBox.min.css">
        <script src="jquery.msgBox.min.js"></script>
        <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script src="AndroidToast.js"></script>
        <link rel="stylesheet" href="AndroidToast.css"/>
    </head>
    <body>
        <script>

            function copyStringToClipboard (str) {
                var el = document.createElement('textarea');
                el.value = str;
                el.setAttribute('readonly', '');
                el.style = {position: 'absolute', left: '-9999px'};
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
            function Cite(){
                        var bib = 
                            '@book\n'+
                            '{\n'+
                                '\t<?php echo $handle?>,\n'+
                                '\tauthor = "<?php echo str_replace(' &',',',$data['author']);?>",\n'+
                                '\ttitle = "<?php echo $data['title']?>",\n'+
                                '\tpublisher = "<?php echo $data['publisher'];?>",\n'+
                                '\tedition = "<?php echo $data['edition'];?>",\n'+
                                '\tyear = "<?php echo $data['published']?>",\n'+
                                '\turl = "<?php echo $currenturl;?>"\n'+
                                '}';
                        $("#overlay").removeClass("hide-overlay");
                        $('.msgBox-testArea').msgBox(
                        {
                            title: 'Citação em BibTex',
                            type: 'info',
                            message: bib,
                            buttons: [
                                {
                                    text: 'COPIAR',
                                    callback: function() {
                                            copyStringToClipboard(bib);
                                            var toast = $(window).AndroidToast({
                                                message : "Copiado para área de transferência"
                                            });
                                            toast.AndroidToast('show');
                                        $("#overlay").addClass("hide-overlay");
                                    }
                                },
                                {
                                    text: 'FECHAR',
                                    callback: function() {
                                        $("#overlay").addClass("hide-overlay");
                                    }
                                }
                            ],
                        });
                    };

                    
            </script>
        <main>
            <div id="overlay" class="hide-overlay"></div>
            <div class="msgBox-testArea modal"></div>
            <button id="btn-cite-top" class="float-btn mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onclick="Cite();">
                <i class="material-icons">format_quote</i>
            </button>
            <div id="cmd-share-top" data-share-position="middleLeft" class="float-btn" data-share-icon-style="box" data-share-networks="Mailto,Facebook,Twitter,Linkedin,Pinterest,evernote">
                <div class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect">
                    <i class=" need-share-button_button material-icons pointer material-icons" id="icon-share-top">&#xe80d</i>
                </div>
            </div>  
            <iframe id="pdf" src="web/viewer.html?file=https://yacdn.org/serve/<?php echo $data["link"];?>"></iframe>
            <div id="panel">
                <div id="cover">
                    <h5 id="pubtitle"><?php echo $data["title"];?></h5>
                </div>
                <button id="btn-cite" class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" onclick="Cite();">
                    <i class="material-icons">format_quote</i>
                </button>
                
                <a onclick="goBack();" id="cmd-back">
                    <i class="material-icons pointer" id="icon-back">arrow_back</i>
                </a>
                <div id="cmd-share" data-share-position="middleLeft" data-share-icon-style="box" data-share-networks="Mailto,Facebook,Twitter,Linkedin,Pinterest,evernote">
                    <i class=" need-share-button_button material-icons pointer" id="icon-share">share</i>
                </div>    
                <div id="info">
                    <form action="#">
                        <div id="author" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text"value="<?php echo $data["author"];?>" readonly>
                            <label class="mdl-textfield__label" for="author">Autor</label>
                        </div>
                        <div id="publishdate" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $data["published"];?>" readonly>
                            <label class="mdl-textfield__label" for="publishdate">Publicação</label>
                        </div>
                        <div id="language" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $data["lang"];?>" readonly>
                            <label class="mdl-textfield__label" for="language">Idioma</label>
                        </div>
                        <div id="subject" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $data["subject"];?>" readonly>
                            <label class="mdl-textfield__label" for="subject">Assunto</label>
                        </div>
                        <div id="type" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $data["type"];?>" readonly>
                            <label class="mdl-textfield__label" for="type">Tipo</label>
                        </div>
                        <div id="ufscid" class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $handle;?>" readonly>
                            <label class="mdl-textfield__label" for="ufscid">Repo ID</label>
                        </div>
                    </form>
                    <footer>
                        <div id="white-grad">
                    </div>
                        <div id="container" style="background-color: white;height: 90px;">
                        <img id="logo_pget" src="resources/pget_name.png" alt="Logo PGET">
                        <img id="brasao" src="resources/brasao_UFSC_vertical_sigla.svg" alt="Brasao UFSC" srcset="resources/brasao_UFSC_vertical_sigla.svg">
                    </div>
                    </footer>
                </div>
            </div> 
        </main>
        <script src="src/js/needsharebutton.js"></script>
        <script>
            new needShareDropdown(document.getElementById('cmd-share'));
            new needShareDropdown(document.getElementById('cmd-share-top'));
        </script>
    </body>
</html>