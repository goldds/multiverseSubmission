$("plus_edu").click(function add_School()
        {
            var n_divs = $("#School_fields > div").length;
            if(n_divs < 9)
            {
                var html = '<div id="School' + (n_divs + 1) + '">\n<p>Year: <input type="text" name="edu_year' + (n_divs + 1) + '" value="">\n<input type="button" value="-" onclick="$(\'#School' + (n_divs + 1) + '\').remove(); fix_School(); return false;"></p> School: <br> <textarea name="school'  + (n_divs + 1) + '" rows="1" cols="80"></textarea></div>\n';
                $('#School_fields').append(html);
            }
            else
                alert("Maximum of nine School entries exceeded");
        });

function fix_School()
{
    var div_tags = $("#School_fields").children("div");
    for(j = 0; j < div_tags.length; j++)
    {
        $(div_tags[j]).attr('id', 'School' + (j + 1)); //Modifica el id del div de cada School
        $(div_tags[j]).find("textarea").attr('name', 'desc' + (j + 1)); //Modifica el name del textarea
        input_tags = $(div_tags[j]).find("input"); // Para obtener las tags input del div School
        $(input_tags[0]).attr('name', 'year' + (j + 1)); //Modificar el atributo year del input Year
        $(input_tags[1]).attr('onclick', '$(\'#School' + (j + 1) + '\').remove(); fix_School(); return false;'); //Modificar el atributo onclick del input Button
        
    }
}

  