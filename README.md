# Symfony application using data objects in forms instead of Doctrine entities

This app is a bit of an architectural prototype allowing me to come up with an acceptable approach to CRUD operations without having to use entities as the form data.

As they say, there are many ways to get to Rome. This is what I am doing and I can't stop thinking: **Is there a better way to do this?**

**Getting started**

1. Check out
2. `composer install`
3. `symfony serve`

If you're not checking this out to run it on your machine, check what I do in `src/Controller/DefaultController::update`,
`src/Form/PersonUpdateType`, `src/Crud/PersonUpdateDto.php`, `src/Crud/PersonDto.php` and finally `src/Entity/Person::(create|update)With()`
to get a gist of what I'm doing here. 

**About the code**

I tried to create a minimal example with two entities: `Person` and `Address`. The demo is so simplified that the code
might not make sense on a human level ("why wouldn't I be able to update the boss later?"). Don't get stuck into that thinking,
there is no reason for any of the code other than demonstrate the idea behind the architecture. 

A few key business requirements I tried to demonstrate:

1. `Person` has self-referencing ManyToOne association that is optional. This association can be set when creating a new `Person` entity, but can't be updated
2. `Person` has OneToOne association with `Address`. The `Address` can be created when creating the `Person`, but it can also be created when **updating** the person later. 
3. The `Address` can be both created and updated at a later stage.

The item 3 above is one of my main questions: as I'm working with separate DTO for Create and Update action, *something* must
be able to decide what operation am I doing. Creating, or updating? What is the best *something*? Is it the Controller? the form? The parent DTO? Hmmm!  

## Benefits

1. All entities are always valid
2. Lots of benefits of stemming from not having any direct setters
3. Entity update can be easily re-tried if optimistic locking in used to manage concurrency 

## Downsides

1. A bit of boilerplate
2. Associations require a bit of extra work to convert the entities to DTO and back
3. Symfony Forms don't exactly like working with DTO when using Collection-based form types
4. `UniqueEntity` validator doesn't work out of the box
