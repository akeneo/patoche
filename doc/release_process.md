# Release process of the Onboarder platform

This document describes all the step necessary to release an Onboarder platform, ready to deploy into production. 

If for any reason Patoche is unavailable, you can follow the steps detailed bellow to manually tag the Onboarder.

## Summary

The Onboarder platform currently consists of 4 GitHub repositories:
- the PIM Onboarder bundle: [akeneo/pim-onboarder](https://github.com/akeneo/pim-onboarder),
- the Middleware [akeneo/onboarder-middleware](https://github.com/akeneo/onboarder-middleware),
  which also contains the Overseer (until it is replaced by adding its features to the Middleware),
- the Supplier Onboarder [akeneo/onboarder](https://github.com/akeneo/onboarder),
- the deployment infrastructure [akeneo/helm-onboarder](https://github.com/akeneo/helm-onboarder).

Releasing the Onboarder consists of the following steps:
1. Prerequisites
2. Updating the dependencies of the bundle, the Middleware, the Overseer, and the Supplier Onboarder
3. Updating PIM Enterprise Cloud to test the latest PIM Onboarder bundle
4. Deploying the built images
5. Testing a migration from the latest stable version
6. Tagging the GitHub repositories `akeneo/pim-onboarder`, `akeneo/onboarder-middleware`, and `akeneo/onboarder`
7. Tagging the production Docker images for the Middleware, the Overseer, and the Onboarder
8. Updating the PEC pull-request to use the recently tagged PIM Onboarder bundle
9. Releasing the new version of PEC with the updated bundle
10. Updating and tagging Helm Onboarder
11. Updating and deploying the documentation (only for major and minor releases, not for patches)

All those steps are fully described below.

## 1. Prerequisites

We never tag the repositories from the master branch. We must use the stable branches corresponding to minor versions.
For example, if you tag an Onboarder platform `2.2.2`, you will do that from the `2.2` branch. If you tag a `3.0.0`,
you will do it from the `3.0` branch.

As a consequence, tagging a new minor or major version implies to create the corresponding branch. This is to be done on
- the PIM Onboarder bundle: [akeneo/pim-onboarder](https://github.com/akeneo/pim-onboarder),
- the Middleware [akeneo/onboarder-middleware](https://github.com/akeneo/onboarder-middleware),
- the Supplier Onboarder [akeneo/onboarder](https://github.com/akeneo/onboarder),
- the deployment infrastructure [akeneo/helm-onboarder](https://github.com/akeneo/helm-onboarder).

### Create the branch

Let's say we are tagging the new major version `3.0`. Checkout the master branch and pull it to be sure to be up to date.
Then create a new `3.0` branch and push it on the repository.
```bash
$ git switch master
$ git pull --rebase origin master
$ git switch -c 3.0
$ git push origin 3.0
```

### Update the branch content

Once the new branch created, there are a few values to update in each repositories.

First, you need to update the branch version in CircleCI configuration. Replace `master` with the name of the branch you
just created:
- in the Middleware [here](https://github.com/akeneo/onboarder-middleware/blob/master/.circleci/config.yml#L179),
  [here](https://github.com/akeneo/onboarder-middleware/blob/master/.circleci/config.yml#L205)
  and  [here](https://github.com/akeneo/onboarder-middleware/blob/master/.circleci/config.yml#L227),
- in the Supplier Onboarder [here](https://github.com/akeneo/onboarder/blob/master/.circleci/config.yml#L175),
  [here](https://github.com/akeneo/onboarder/blob/master/.circleci/config.yml#L194),
  and [here](https://github.com/akeneo/onboarder/blob/master/.circleci/config.yml#L210).

Then update the Docker image tag in the Makefiles. Replace `prod_master` with `prod_<the name of the branch>`.
In our example, we created a new branch `3.0`, so the Docker image tag will be `prod_3.0`. You need to do that:
- in the [Middleware Makefile](https://github.com/akeneo/onboarder-middleware/blob/master/Makefile#L1),
- in the [Overseer Makefile](https://github.com/akeneo/onboarder-middleware/blob/master/overseer/Makefile#L2),
- in the [Supplier Onboarder Makefile](https://github.com/akeneo/onboarder/blob/master/Makefile#L1).

Finally, you also need to change the `app version` of the Overseer in the
[Overseer Makefile](https://github.com/akeneo/onboarder-middleware/blob/master/overseer/Makefile#L1).
Set it to the tag you are going to release (in our example, this will be `3.0.0`).

## 2. Updating the dependencies

We should always check all dependencies are up-to-date before releasing a new version of the Onboarder.

A couple of dependencies have their version fixed to the patch directly in `composer.json`:
- `google/cloud-pubsub` for the PIM Onboarder bundle, the Middleware and the Supplier Onboarder,
- `superbalist/flysystem-google-storage` for the PIM Onboarder bundle and the Supplier Onboarder.
We should check if those versions can be updated (like if a new patch was released).

We should also check the front-end dependencies (`packages.json`) for the Supplier Onboarder.

Then all lock files can be updated as follow:
```bash
$ make update-dependencies
```
This is to be done on:
- `akeneo/onboarder-middleware` repository => in root directory,
- `akeneo/onboarder-middleware` repository => in `overseer` directory,
- `akeneo/onboarder` repository.

Then create some pull-requests with the updated dependencies and merge them if their CIs are green.

Merging the Onboarder pull-requests will trigger CircleCI to build of the Docker images for the Middleware,
the Overseer, and the Supplier Onboarder. It will update the `prod_master` (or `prod_x.y` if you merge on the `x.y`
branch). Check on Google Cloud Console that the Docker images are present on the `aob-dev` Docker registry.

## 3. Updating PIM Enterprise Cloud

To test the Onboarder platform, we also need to deploy a PIM Enterprise Cloud (PEC) with the PIM Onboarder bundle activated.

For that, update the PEC `composer.json` to replace the `akeneo/pim-onboarder` version by the last commit of the branch we are going to tag from.
For instance:
- for a `x.y` branch, `"akeneo/pim-onboarder": "2.2.1"` will become `"akeneo/pim-onboarder": "2.2.x-dev#8ac5961c2adf8345bd5028eec1892e9259113ae5@dev"`,
- for `master`, `"akeneo/pim-onboarder": "2.2.1"` will become `"akeneo/pim-onboarder": "dev-master#218b3b15c25ab19ca2446db3fdcfbce14980707b@dev"`.

Then update the lock file by running:
```bash
$ docker-compose run --rm fpm php -d memory_limit=-1 /usr/local/bin/composer update --prefer-dist --optimize-autoloader --no-interaction --no-scripts
```

Then open a pull-request with the result. This will trigger the build of the PEC helm chart and Docker images, and
deploy the result. The deployment will provide you a PEC with PIM Onboarder bundle deactivated. Check that this PIM
works: login, quickly check the product grid, the assets, reference entities, …

**Remember to validate (click on "Success") or invalidate (click on "Abort") the deployment**. It will destroy the
environment, so it does not consume resources on the Kubernetes cluster for nothing.

Creating the pull-request on PEC will trigger Jenkins to build a Docker image with the PIM Onboarder bundle activated.
It will push it on the `akeneo-ci` Docker registry. Check on Google Cloud Console that the Docker images are present.

## 4. Deploying the built images

You can now trigger a build on Jenkins from [akeneo/helm-onboarder](https://ci.akeneo.com/job/akeneo/job/helm-onboarder/).
You need to launch it from the correct branch: for instance `x.y` if you tag a `x.y.z`.

Fill the Jenkins form with the correct values, i.e. the Docker image tags that were generated by both Circle CI and Jenkins at the end of steps 2 and 3, respectively.

Once the PM ensured the Onboarder is working as expected, validate the deployment to destroy it, **but do not accept the tagging process!** Not yet.

## 5. Testing a migration

Deploy the latest stable version of the Onboarder following [this documentation](https://github.com/akeneo/helm-onboarder/blob/master/doc/deploy.md).

Then manually upgrade the Onboarder following the content of the [UPGRADE.md](https://github.com/akeneo/helm-onboarder/blob/master/UPGRADE.md) from `akeneo/helm-onboarder`.

Let the PM test this environment too, then destroy it.

## 6. Tagging the GitHub repositories

You need to tag the following GitHub repositories:
- [akeneo/pim-onboarder](https://github.com/akeneo/pim-onboarder),
- [akeneo/onboarder-middleware](https://github.com/akeneo/onboarder-middleware),
- [akeneo/onboarder](https://github.com/akeneo/onboarder).

If you are tagging a new major or minor release, you can directly create a GitHub release.
For that, go on `https://github.com/akeneo/the-onboarder-repository/releases`, click on `Draft a new release`, select
the right target branch to tag from and fill the tag and version fields (see illustration below).

![draft a new release](release.jpg "draft a new release")

If you are tagging a patch (for instance `2.2.2`) for an existing major or minor version, just create the new git tag on each repository as follow:
```bash
$ git tag v2.2.2
$ git push origin v2.2.2
```

## 7. Tagging the production Docker images of the Onboarder

These images are for the Middleware, the Overseer and the Supplier Onboarder.
They are the same as the `prod_x.y` that were previously deployed in step 4 and 5,
we are only going use the same tag we just created on GitHub.

This is done by using the Google Cloud CLI and requires to have access to the production Docker registry `akeneo-cloud`,
so the following commands will probably have to be done by a DevOps or a SRE.

Assuming we are tagging the release `x.y.z` from the `prod_x.y` development tags:
```bash
$ gcloud container images add-tag eu.gcr.io/aob-dev/onboarder-middleware:prod_x.y eu.gcr.io/akeneo-cloud/onboarder-middleware:x.y.z
$ gcloud container images add-tag eu.gcr.io/aob-dev/onboarder-middleware:prod_x.y-fpm eu.gcr.io/akeneo-cloud/onboarder-middleware:x.y.z-fpm
$ gcloud container images add-tag eu.gcr.io/aob-dev/overseer:prod_x.y eu.gcr.io/akeneo-cloud/overseer:x.y.z
$ gcloud container images add-tag eu.gcr.io/aob-dev/onboarder:prod_x.y eu.gcr.io/akeneo-cloud/onboarder:x.y.z
$ gcloud container images add-tag eu.gcr.io/aob-dev/onboarder:prod_x.y-fpm eu.gcr.io/akeneo-cloud/onboarder:x.y.z-fpm
```

## 8. Updating the PEC pull-request to use the recently tagged PIM Onboarder bundle

We opened a pull-request in step 3. Now that the Onboarder is tagged, we can update this pull-request with the new tag.
- Use this tag as `akeneo/pim-onboarder` version in `composer.json`.
- Run again `composer update` as in step 3 to update `composer.lock`.
- In the PEC `Jenkinsfile`, increase the [chartRelease](https://github.com/akeneo/pim-enterprise-cloud/blob/master/.ci/Jenkinsfile#L11):
  for instance if it is `x.y.z-01`, change it for `x.y.z-02`. This will allow us to tag a new version of PEC with the newly released PIM Onboarder bundle inside.
- Commit and push, this will trigger a new deployment.
- Validate the deployment and merge the pull-request.

## 9. Releasing the new version of PEC with the updated bundle

Merging the PEC pull-request in the previous step triggers a new Jenkins build on the target branch.
This build can be found in this list: https://ci.akeneo.com/job/akeneo/job/pim-enterprise-cloud/

Once again, we need to validate the deployment. Once the PEC environment is destroyed, Jenkins will propose to tag a new version of PEC. **Accept**.

## 10. Updating and tagging Helm Onboarder

Now that both the Onboarder platform and PEC are tagged, we can update and tag Helm Onboarder. The process is very close to the tagging process of PEC.
- Upgrade [lastRelease](https://github.com/akeneo/helm-onboarder/blob/master/.ci/Jenkinsfile#L12) and
  [defaultPimMasterTag](https://github.com/akeneo/helm-onboarder/blob/master/.ci/Jenkinsfile#L18) in the Jenkinsfile
- Upgrade PEC version in the Terraform [main test file](https://github.com/akeneo/helm-onboarder/blob/master/.ci/pim/main.tf#L10)
- Update the Helm [default values](https://github.com/akeneo/helm-onboarder/blob/master/onboarder/values.yaml#L74) with the new tag, for the Middleware, the Overseer, and the Supplier Onboarder
- Add the new version in the changelog

Create a pull-request with that. It will trigger an environment that you will have to validate. Then merge the pull-request.

Like for PEC, merging the pull-request will trigger a deployment on the target branch. Fill the form, launch the deployment and validate it.
Then Jenkins will propose to tag Helm Onboarder with the new version filled in the `lastRelease` variable of the Jenkinsfile. Like for PEC, **accept**.

## 11. Updating and deploying the documentation

Now the Onboarder is fully tagged. The last step to finish the release is to update the PIM documentation, as
there is a section explaining how to install the PIM Onboarder bundle in an on-premise or PaaS Akeneo PIM.

For each new minor or major release, we need to update [this file](https://github.com/akeneo/pim-docs/blob/3.2/onboarder/installation/index.rst)
(here, the link points to the 3.2 branch of the documentation, but it needs to be done for the correct branch according to the PIM Onboarder bundle compatibility).

Once the documentation pull-request is merged, the modified branch needs to be deployed on [docs.akeneo.com](https://docs.akeneo.com/).
