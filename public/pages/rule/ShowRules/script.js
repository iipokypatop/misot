var graph = null;
var paper = null;
var current_sequence = null;

$(document).ready(function () {

    graph = new joint.dia.Graph;

    paper = new joint.dia.Paper({
        el: $('#paper'),
        width: 2000,
        height: 400,
        gridSize: 1,
        model: graph,
        interactive: function (el) {
            return el.model.isLink() === true;
        },
        defaultLink: new joint.shapes.fsa.Arrow,
        markAvailable: true
    });


    graph.on('remove', function (cell) {
        //alert('  cell with id ' + cell.id + '   to the graph.')
        $("#" + cell.id).prop('checked', false);
        removed_cells[cell.id] = cell;
    });

});

function loadData(index) {

    current_sequence = index;

    graph.fromJSON(sequences[index]);

    $(graph.getElements()).each(function (index, element) {


        /*element.set('transition', function (path, value, opt, delim) {
         return false;
         })*/
    })

}

function disableAllOtherGroups(that) {
    $("[name^='form[correct_sequence][sviaz]'][type='checkbox']").prop('disabled', true);
}


function enableGroup(that) {
    $("input[group_id='" + $(that).attr('group_id') + "']")
        .filter("[type='checkbox']")
        .prop('disabled', false)

}

var removed_cells = [];

function removeCell(id) {
    var cell = graph.getCell(id);
    if (cell !== null) {
        cell.remove();
        removed_cells[id] = cell;
    }
}

function restoreCell(id) {
    graph.addCell(removed_cells[id])
}


function toggleLink(that) {
    var id = $(that).data('cell_id');
    if ($(that).prop('checked') === false) {
        removeCell(id);
    } else {
        restoreCell(id);
    }
}

function attachResultJson() {
    $('[name="form[correct_sequence][json]"]').val(
        JSON.stringify(
            graph.toJSON()
        )
    )


}

