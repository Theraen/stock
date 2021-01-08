jQuery(document).ready(function() {

    var $ingredientsCollectionHolder = $('ul.ingredients');


    $ingredientsCollectionHolder.data('index', $ingredientsCollectionHolder.find('input').length);

    $('body').on('click', '.add_item_link_ingredient', function(e) {
        var $ingredientCollectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        addFormIngredientToCollection($ingredientCollectionHolderClass);
    })
});

function addFormIngredientToCollection($ingredientCollectionHolderClass) {

    var $ingredientsCollectionHolder = $('.' + $ingredientCollectionHolderClass);

    var prototypeIngredient = $ingredientsCollectionHolder.data('prototype');


    var indexIngredient = $ingredientsCollectionHolder.data('index');

    var newFormIngredient = prototypeIngredient;

    newFormIngredient = newFormIngredient.replace(/__name__/g, indexIngredient);

    $ingredientsCollectionHolder.data('index', indexIngredient + 1);

    var $newFormLiIngredient = $('<li></li>').append(newFormIngredient);

    $ingredientsCollectionHolder.append($newFormLiIngredient)

    $newFormLiIngredient.append('<a href="#" class="remove-ingredient btn btn-circle btn-danger mb-3"><i class="fas fa-minus"></a>');

    $('.remove-ingredient').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

jQuery(document).ready(function() {

    var $preparationCollectionHolder = $('ul.preparation');


    $preparationCollectionHolder.data('index', $preparationCollectionHolder.find('textarea').length);



    $('body').on('click', '.add_item_link_preparation', function(e) {
        var $preparationCollectionHolderClass = $(e.currentTarget).data('collectionHolderClass');


        addFormPreparationToCollection($preparationCollectionHolderClass);
    })
});

function addFormPreparationToCollection($preparationCollectionHolderClass) {

    var $preparationCollectionHolder = $('.' + $preparationCollectionHolderClass);


    var prototypePreparation = $preparationCollectionHolder.data('prototype');


    var indexPreparation = $preparationCollectionHolder.data('index');

    var newFormPreparation = prototypePreparation;

    newFormPreparation = newFormPreparation.replace(/__name__/g, indexPreparation);

    $preparationCollectionHolder.data('index', indexPreparation + 1);

    var $newFormLiPreparation = $('<li></li>').append(newFormPreparation);

    $preparationCollectionHolder.append($newFormLiPreparation)

    $newFormLiPreparation.append('<a href="#" class="remove-preparation btn btn-circle btn-danger mb-3"><i class="fas fa-minus"></a>');

    $('.remove-preparation').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}


