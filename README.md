<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Laravel Daily Mentorship

[Laravel Daily Mentorship](https://laraveldaily.com/lesson/travel-api/client-specification-into-plan-of-action)

## How this works

- Take the project requirements
- Build
- Watch the course provided by Laravel Daily
- Compare code 


### Lesson 1 - Database + Models

#### What I did - [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/a5c5ad7d1409a4ee78c9fbb4dbdd4a70d92d6275)

- Generated migrations using UUIDS 
- Installed LaraStan and spatie/permissions
- Generated Models
- Created two cli commands - create role and create user

#### What I could improve [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/d5e46207b04c319e8755ecc9ef008974ab4cb45e)

- Forgot to add unique to slug in migration.
- Used datetime rather than date on migrations.
- Didn't add protected fillable fields to the model.
- Observers - Never heard of these, I would normally generate the slug in the controller but for the purpose of learning something new going with this method. 
- Installed cviebrock/eloquent-sluggable.
- Rename the model Travels to Travel.
- Model Attributes - Never heard of these but learning something new.

#### Takeaway

- I'm working in an "old laravel" way rather than utilising newer features.
- Planned to implement quite a few things in the controllers rather than create attributes and observers.
- Need to pay attention to proper English rules and naming conventions (Tours/Tour and Travels/Travel).


### Lesson 2 - Public Endpoint /travels with Feature Tests

#### What I did - [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/74199d9909a4b553526e81e72e1fe2c57787772d)

- Created API endpoint
- Created Travel Resource to only return certain fields
- Removed Sluggable package from model - was causing some errors
- Return all Travel db entries when api endpoint is visited

#### What I could improve [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/68ae94e9333c23c6cc34688bbbec2a0219c068f9)

- Did not add versioning to the API
- Did not use pagination for results
- Learnt about adding prefixes to routes

#### Tests

~~ Without sounding like I gave up, I am skipping the writing tests part. 

Yes I need to learn more about writing tests however coding in my spare time should be fun, it is not fun to spend time chasing environment errors that stop your tests from running. ~~

After sleeping on it I solved the environment errors causing the tests not to run, so I pushed a third commit with working tests and the fixes. [See commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/f228c0b819bf9a3b573861c7c3a7beb2d73d69b2)

### Lesson 3 - Public Endpoint /travels/[slug]/tours with Tests

#### What I did - [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/28258f40ac56071296ed52274b25bc7bb61cf61e)

- Created API endpoint
- Created Tour factory
- Created Tour Resource to only return certain fields
- Return all related Tour entries when api endpoint is visited
- Created feature test 

Note: there was some confusion over naming conventions such as Tours/Tour. This could have been planned a little better but will stick with what we have now as it doesn't cause any errors. 

#### What I could improve [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/3938393d2033027607021f287f7ba027ae6b5696)

- Route Model binding - I always forget to do this 
- Makes sense to order the results by start date
- Format the price field when output in the api
- Added an extra test for the formatting of the price

### Lesson 4 - Tours Filtering and Ordering

#### What I did - [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/f2b56492beb9ec4afb9a04f821949655b7adcc49)

- Added filters to the api query
- Added validation for query parameters

#### What I could improve [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/14c753a7f81a09e228aad805be09f8220f708503)

- So many tests 
- Added sort by to the api query
- Created a ToursListRequest file (normally I would just handle this in the controller but its good practice to do this)

### Lesson 5 - Create Users: Artisan Command

#### What I did - [see earlier commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/a5c5ad7d1409a4ee78c9fbb4dbdd4a70d92d6275#diff-93c812c0257339879ea0fb0d39e9e05016da9fb69df12e498a36580a1e28b94c)

- I actually created the command in my first commit and was pretty happy with it.

#### What I could improve [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/9a8bfa34ea3c1e959c9b4707d43e6762a754ce52)

- Instead of requiring all parameters in a single command it now asks for details in stages 
- Makes use of validation  
- Created a Role seeder

### Lesson 6 - Admin Endpoint: Create Travels

#### What I did - [see earlier commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/cc6f3862d5551aa44e398b91e3431f258a945044)

- Created a POST api route
- Created a TravelRequest to handle the incoming data
- Added store function to the TravelController

Having never worked with auth and api routes I skipped those requirements to learn from the course. 

#### What I learnt/improved [see commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/cc6f3862d5551aa44e398b91e3431f258a945044)

- I learnt a LOT about middleware here 
- Create middleware to check roles
- Created tests for the creation api end points

### Lesson 7 - Admin Endpoint: Create Tours

#### What I did - [see earlier commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/04081c88d49468a9561027a25926f66cf9d67dba)

Based on everything I learnt in the previous lesson, this one was pretty easy. 

Even remembered the model binding in the route :) 

- Created a POST api route
- Created a TourRequest to handle the incoming data
- Added store function to the TourController
- Created tests

### Lesson 8 - Admin Endpoint: Edit Travel

#### What I did - [see earlier commit](https://github.com/stitchtrove/TravelApiWithLaravel/commit/819d5c2368cdf023177d120febdb055a8429c894)

Again, fairly straightforward using what I'd already learnt.

- Created a PUT api route
- Added update function to the TourController
- Created tests