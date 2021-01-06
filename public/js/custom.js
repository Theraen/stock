jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $ingredientsCollectionHolder = $('ul.ingredients');
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $ingredientsCollectionHolder.data('index', $ingredientsCollectionHolder.find('input').length);

    $('body').on('click', '.add_item_link_ingredient', function(e) {
        var $ingredientCollectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        // add a new tag form (see next code block)
        addFormIngredientToCollection($ingredientCollectionHolderClass);
    })
});

function addFormIngredientToCollection($ingredientCollectionHolderClass) {
    // Get the ul that holds the collection of tags
    var $ingredientsCollectionHolder = $('.' + $ingredientCollectionHolderClass);

    // Get the data-prototype explained earlier
    var prototypeIngredient = $ingredientsCollectionHolder.data('prototype');

    // get the new index
    var indexIngredient = $ingredientsCollectionHolder.data('index');

    var newFormIngredient = prototypeIngredient;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newFormIngredient = newFormIngredient.replace(/__name__/g, indexIngredient);

    // increase the index with one for the next item
    $ingredientsCollectionHolder.data('index', indexIngredient + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLiIngredient = $('<li></li>').append(newFormIngredient);
    // Add the new form at the end of the list
    $ingredientsCollectionHolder.append($newFormLiIngredient)

    $newFormLiIngredient.append('<a href="#" class="remove-ingredient btn btn-circle btn-danger mb-3"><i class="fas fa-minus"></a>');

    $('.remove-ingredient').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    var $preparationCollectionHolder = $('ul.preparation');
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $preparationCollectionHolder.data('index', $preparationCollectionHolder.find('input').length);



    $('body').on('click', '.add_item_link_preparation', function(e) {
        var $preparationCollectionHolderClass = $(e.currentTarget).data('collectionHolderClass');

        // add a new tag form (see next code block)
        addFormPreparationToCollection($preparationCollectionHolderClass);
    })
});

function addFormPreparationToCollection($preparationCollectionHolderClass) {
    // Get the ul that holds the collection of tags
    var $preparationCollectionHolder = $('.' + $preparationCollectionHolderClass);

    // Get the data-prototype explained earlier
    var prototypePreparation = $preparationCollectionHolder.data('prototype');

    // get the new index
    var indexPreparation = $preparationCollectionHolder.data('index');

    var newFormPreparation = prototypePreparation;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);


    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newFormPreparation = newFormPreparation.replace(/__name__/g, indexPreparation);

    

    // increase the index with one for the next item
    $preparationCollectionHolder.data('index', indexPreparation + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLiPreparation = $('<li></li>').append(newFormPreparation);
    // Add the new form at the end of the list
    $preparationCollectionHolder.append($newFormLiPreparation)

    $newFormLiPreparation.append('<a href="#" class="remove-preparation btn btn-circle btn-danger mb-3"><i class="fas fa-minus"></a>');

    $('.remove-preparation').click(function (e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}