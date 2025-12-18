<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $display_order
 * @property numeric $price
 * @property int|null $duration_days
 * @property string $service_type
 * @property int|null $boost_multiplier
 * @property array<array-key, mixed>|null $features
 * @property bool $is_active
 * @property bool $is_popular
 * @property string|null $color
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompanyAddonService> $companyServices
 * @property-read int|null $company_services_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereBoostMultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddonServiceConfig withoutTrashed()
 */
	class AddonServiceConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int|null $payment_id
 * @property string $ad_type
 * @property string $title
 * @property string|null $description
 * @property string|null $image_url
 * @property string|null $target_url
 * @property numeric $price
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property int $impressions_count
 * @property int $clicks_count
 * @property numeric $ctr
 * @property int $display_order
 * @property array<array-key, mixed>|null $targeting
 * @property bool $is_active
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\Payment|null $payment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereAdType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereClicksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereCtr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereImpressionsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereTargetUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereTargeting($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advertisement withoutTrashed()
 */
	class Advertisement extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $job_id
 * @property int $user_id
 * @property string|null $cv_path
 * @property string|null $cover_letter
 * @property string|null $portfolio_url
 * @property string $status
 * @property string|null $internal_notes
 * @property \Illuminate\Support\Carbon|null $viewed_at
 * @property \Illuminate\Support\Carbon|null $responded_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Conversation|null $conversation
 * @property-read \App\Models\Job $job
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCoverLetter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereCvPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereInternalNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application wherePortfolioUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereRespondedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application whereViewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Application withoutTrashed()
 */
	class Application extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobs
 * @property-read int|null $jobs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $logo
 * @property string|null $description
 * @property string $sector
 * @property string|null $website
 * @property string|null $address
 * @property string|null $city
 * @property string $country
 * @property string $status
 * @property string $subscription_plan
 * @property int|null $active_subscription_id
 * @property int|null $jobs_limit
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $contacts_limit
 * @property int $jobs_posted_this_month
 * @property int $contacts_used_this_month
 * @property string|null $quota_reset_at
 * @property int $can_access_cvtheque
 * @property int $can_boost_jobs
 * @property int $can_see_analytics
 * @property int $priority_support
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobs
 * @property-read int|null $jobs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Recruiter> $recruiters
 * @property-read int|null $recruiters_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereActiveSubscriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCanAccessCvtheque($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCanBoostJobs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCanSeeAnalytics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereContactsLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereContactsUsedThisMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereJobsLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereJobsPostedThisMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company wherePrioritySupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereQuotaResetAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereSector($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereSubscriptionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Company withoutTrashed()
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int $addon_services_config_id
 * @property int|null $payment_id
 * @property int|null $related_job_id
 * @property int|null $related_user_id
 * @property \Illuminate\Support\Carbon $purchased_at
 * @property \Illuminate\Support\Carbon|null $activated_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property bool $is_active
 * @property int $views_count
 * @property int $clicks_count
 * @property int|null $uses_remaining
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\AddonServiceConfig $config
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\Job|null $relatedJob
 * @property-read \App\Models\User|null $relatedUser
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereActivatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereAddonServicesConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereClicksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService wherePurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereRelatedJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereRelatedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereUsesRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService whereViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyAddonService withoutTrashed()
 */
	class CompanyAddonService extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobs
 * @property-read int|null $jobs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContractType whereUpdatedAt($value)
 */
	class ContractType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_one
 * @property int $user_two
 * @property int $application_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Application $application
 * @property-read \App\Models\Message|null $lastMessage
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User $userOne
 * @property-read \App\Models\User $userTwo
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUserOne($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Conversation whereUserTwo($value)
 */
	class Conversation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $job_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Job $job
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereJobId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Favorite whereUserId($value)
 */
	class Favorite extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int|null $category_id
 * @property int|null $location_id
 * @property int|null $contract_type_id
 * @property int $posted_by
 * @property string $title
 * @property string $description
 * @property string|null $requirements
 * @property string|null $benefits
 * @property numeric|null $salary_min
 * @property numeric|null $salary_max
 * @property bool $salary_negotiable
 * @property string|null $experience_level
 * @property string $status
 * @property bool $is_featured
 * @property int $views_count
 * @property \Illuminate\Support\Carbon|null $application_deadline
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $closed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Application> $applications
 * @property-read int|null $applications_count
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\ContractType|null $contractType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $favoritedBy
 * @property-read int|null $favorited_by_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Favorite> $favorites
 * @property-read int|null $favorites_count
 * @property-read \App\Models\Location|null $location
 * @property-read \App\Models\User $postedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereApplicationDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereContractTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereExperienceLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job wherePostedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSalaryMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSalaryMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereSalaryNegotiable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job whereViewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Job withoutTrashed()
 */
	class Job extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $country
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $jobs
 * @property-read int|null $jobs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Location whereUpdatedAt($value)
 */
	class Location extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $conversation_id
 * @property int $sender_id
 * @property string $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conversation $conversation
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $company_id
 * @property string $payable_type
 * @property int $payable_id
 * @property numeric $amount
 * @property numeric $fees
 * @property numeric $total
 * @property string $payment_method
 * @property string|null $transaction_reference
 * @property string|null $phone_number
 * @property array<array-key, mixed>|null $payment_provider_response
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $refunded_at
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property string|null $notes
 * @property string|null $failure_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $payable
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereFailureReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereFees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePayableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePayableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentProviderResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereRefundedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment withoutTrashed()
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $display_order
 * @property numeric $price
 * @property int|null $duration_days
 * @property string $service_type
 * @property array<array-key, mixed>|null $features
 * @property bool $is_active
 * @property bool $is_popular
 * @property string|null $color
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserPremiumService> $userServices
 * @property-read int|null $user_services_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PremiumServiceConfig withoutTrashed()
 */
	class PremiumServiceConfig extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property string|null $position
 * @property bool $can_publish
 * @property bool $can_view_applications
 * @property bool $can_modify_company
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company $company
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereCanModifyCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereCanPublish($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereCanViewApplications($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Recruiter whereUserId($value)
 */
	class Recruiter extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $icon
 * @property int $order
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Section withoutTrashed()
 */
	class Section extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $service_type
 * @property bool $is_active
 * @property array<array-key, mixed>|null $config
 * @property string|null $whatsapp_api_token
 * @property string|null $whatsapp_phone_number_id
 * @property string $whatsapp_api_version
 * @property string|null $whatsapp_template_name
 * @property string $whatsapp_language
 * @property string|null $nexah_base_url
 * @property string $nexah_send_endpoint
 * @property string $nexah_credits_endpoint
 * @property string|null $nexah_user
 * @property string|null $nexah_password
 * @property string|null $nexah_sender_id
 * @property string $freemopay_base_url
 * @property string|null $freemopay_app_key
 * @property string|null $freemopay_secret_key
 * @property string|null $freemopay_callback_url
 * @property int $freemopay_init_payment_timeout
 * @property int $freemopay_status_check_timeout
 * @property int $freemopay_token_timeout
 * @property int $freemopay_token_cache_duration
 * @property int $freemopay_max_retries
 * @property numeric $freemopay_retry_delay
 * @property string $default_notification_channel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereConfig($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereDefaultNotificationChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayAppKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayBaseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayCallbackUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayInitPaymentTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayMaxRetries($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayRetryDelay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopaySecretKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayStatusCheckTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayTokenCacheDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereFreemopayTokenTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahBaseUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahCreditsEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahPassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahSendEndpoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereNexahUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereServiceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereWhatsappApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereWhatsappApiVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereWhatsappLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereWhatsappPhoneNumberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceConfiguration whereWhatsappTemplateName($value)
 */
	class ServiceConfiguration extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $company_id
 * @property int $subscription_plan_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $next_billing_date
 * @property string $status
 * @property bool $auto_renew
 * @property int $jobs_posted_this_period
 * @property int $contacts_used_this_period
 * @property \Illuminate\Support\Carbon|null $last_reset_at
 * @property string|null $cancellation_reason
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\SubscriptionPlan $plan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereAutoRenew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereContactsUsedThisPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereJobsPostedThisPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereLastResetAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereNextBillingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereSubscriptionPlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription withoutTrashed()
 */
	class Subscription extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int $display_order
 * @property numeric $price
 * @property int $duration_days
 * @property int|null $jobs_limit
 * @property int|null $contacts_limit
 * @property bool $can_access_cvtheque
 * @property bool $can_boost_jobs
 * @property bool $can_see_analytics
 * @property bool $priority_support
 * @property bool $featured_company_badge
 * @property bool $custom_company_page
 * @property array<array-key, mixed>|null $features
 * @property bool $is_active
 * @property bool $is_popular
 * @property string|null $color
 * @property string|null $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $activeSubscriptions
 * @property-read int|null $active_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereCanAccessCvtheque($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereCanBoostJobs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereCanSeeAnalytics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereContactsLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereCustomCompanyPage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereDisplayOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereFeaturedCompanyBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereJobsLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan wherePrioritySupport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubscriptionPlan withoutTrashed()
 */
	class SubscriptionPlan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $role
 * @property array<array-key, mixed>|null $permissions
 * @property string $password
 * @property string|null $profile_photo
 * @property string|null $bio
 * @property string|null $skills
 * @property string|null $cv_path
 * @property string|null $portfolio_url
 * @property string|null $experience_level
 * @property int $visibility_score
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Application> $applications
 * @property-read int|null $applications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Conversation> $conversations
 * @property-read int|null $conversations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Conversation> $conversationsAsUserOne
 * @property-read int|null $conversations_as_user_one_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Conversation> $conversationsAsUserTwo
 * @property-read int|null $conversations_as_user_two_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $favorites
 * @property-read int|null $favorites_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Job> $postedJobs
 * @property-read int|null $posted_jobs_count
 * @property-read \App\Models\UserPresence|null $presence
 * @property-read \App\Models\Recruiter|null $recruiter
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCvPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereExperienceLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePortfolioUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSkills($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereVisibilityScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $premium_services_config_id
 * @property int|null $payment_id
 * @property \Illuminate\Support\Carbon $purchased_at
 * @property \Illuminate\Support\Carbon|null $activated_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property bool $is_active
 * @property bool $auto_renew
 * @property int|null $uses_remaining
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\PremiumServiceConfig $config
 * @property-read \App\Models\Payment|null $payment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereActivatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereAutoRenew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService wherePremiumServicesConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService wherePurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService whereUsesRemaining($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPremiumService withoutTrashed()
 */
	class UserPremiumService extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $user_id
 * @property int $online
 * @property string|null $last_seen
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence whereLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence whereOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserPresence whereUserId($value)
 */
	class UserPresence extends \Eloquent {}
}

