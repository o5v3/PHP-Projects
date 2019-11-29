<html>
    <head>
        <title>PHP Challenge</title>
    </head>
    <body>
        <!--Se agrupa todo en un div para colocarle un borde.-->
        <div id="main">
        <h1>Prototype Form</h1>
        <p>How do you spend your day?</p>
        <br>
        <div id="header"></div>
        <div id="page"></div>
        </div>

        <script>
        "use strict";

        class Page {

            //0 = Forma Principal
            //1 = Al hacer submit a la forma principal
            //2 = Vista individual
            //3 = Forma de actualizacion
            //4 = Pagina de record añadido
            current_page = 0;
            url = "results.php";
            page = document.getElementById("page");
            header = document.getElementById("header");
            user_data = {};
            message = {};
            data_num = 1;
            
            makeRequest(mode, action, message="") {
                let request = new XMLHttpRequest();
                request.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        action(this.responseText);
                    };
                };
                request.open(mode, this.url, true);
                request.send(message);
            };

            showPage(html) {
                page.innerHTML = html;
            };

            showHeader(html="") {
                header.innerHTML = html;
            };

            showMainForm() {
                this.showHeader();
                this.message["request"] = "Main form";                
                this.makeRequest("POST", this.showPage, JSON.stringify(this.message));
            };

            showFullData() {
                this.message["request"] = "Show full data";
                this.makeRequest("POST", this.showHeader, JSON.stringify(this.message));
            };

            showSentForm() {
                this.showHeader();
                this.setUserData();
                this.message["request"] = "Show sent form"; 
                this.message["data"] = this.user_data;
                this.makeRequest("POST", this.showPage, JSON.stringify(this.message));
            };

            showIndividualData() {
                this.showHeader();
                this.message["request"] = "Update database";
                this.message["dataNum"] = this.data_num;
                this.makeRequest("POST", this.showPage, JSON.stringify(this.message));
            };

            static arraysToObject(keys, values) {
                let newObject = {};
                for (let i = 0; i < keys.length; i++) {
                    newObject[keys[i]] = values[i];
                };
                return newObject;
            };

            setUserData() {
                let elements =  document.getElementsByClassName("userData");
                let keys = [];
                let values = [];
                for (let i = 0; i < elements.length; i++) {
                    keys[i] = elements[i].name;
                    values[i] = elements[i].value;
                };
                this.user_data = Page.arraysToObject(keys, values);
            };

        };
        main = new Page();
        main.showMainForm();
        </script>
        <style>body {text-align: center;} table, td {border: 1px dotted black;} #main {border: 1px dotted black; width: 25%; margin: auto;}</style>
    </body>
</html>