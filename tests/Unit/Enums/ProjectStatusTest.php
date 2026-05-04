<?php

use App\Enums\ProjectStatus;

// --- allowedTransitions ---

test('DRAFT ne peut transitionner que vers SUBMITTED', function () {
    expect(ProjectStatus::DRAFT->allowedTransitions())
        ->toBe([ProjectStatus::SUBMITTED]);
});

test('SUBMITTED peut transitionner vers UNDER_REVIEW, REJECTED ou DRAFT', function () {
    expect(ProjectStatus::SUBMITTED->allowedTransitions())
        ->toBe([ProjectStatus::UNDER_REVIEW, ProjectStatus::REJECTED, ProjectStatus::DRAFT]);
});

test('UNDER_REVIEW peut transitionner vers APPROVED ou REJECTED', function () {
    expect(ProjectStatus::UNDER_REVIEW->allowedTransitions())
        ->toBe([ProjectStatus::APPROVED, ProjectStatus::REJECTED]);
});

test('APPROVED ne peut transitionner que vers ACTIVE', function () {
    expect(ProjectStatus::APPROVED->allowedTransitions())
        ->toBe([ProjectStatus::ACTIVE]);
});

test('ACTIVE peut transitionner vers ON_HOLD, COMPLETED ou CANCELLED', function () {
    expect(ProjectStatus::ACTIVE->allowedTransitions())
        ->toBe([ProjectStatus::ON_HOLD, ProjectStatus::COMPLETED, ProjectStatus::CANCELLED]);
});

test('COMPLETED n a aucune transition autorisee', function () {
    expect(ProjectStatus::COMPLETED->allowedTransitions())
        ->toBe([]);
});

test('CANCELLED ne peut revenir qu en DRAFT', function () {
    expect(ProjectStatus::CANCELLED->allowedTransitions())
        ->toBe([ProjectStatus::DRAFT]);
});

// --- canTransitionTo ---

test('canTransitionTo retourne true pour une transition autorisee', function () {
    expect(ProjectStatus::DRAFT->canTransitionTo(ProjectStatus::SUBMITTED))->toBeTrue();
    expect(ProjectStatus::UNDER_REVIEW->canTransitionTo(ProjectStatus::APPROVED))->toBeTrue();
    expect(ProjectStatus::ACTIVE->canTransitionTo(ProjectStatus::COMPLETED))->toBeTrue();
});

test('canTransitionTo retourne false pour une transition interdite', function () {
    expect(ProjectStatus::DRAFT->canTransitionTo(ProjectStatus::ACTIVE))->toBeFalse();
    expect(ProjectStatus::COMPLETED->canTransitionTo(ProjectStatus::DRAFT))->toBeFalse();
    expect(ProjectStatus::APPROVED->canTransitionTo(ProjectStatus::REJECTED))->toBeFalse();
});

// --- isOperational ---

test('seuls ACTIVE et ON_HOLD sont operationnels', function () {
    expect(ProjectStatus::ACTIVE->isOperational())->toBeTrue();
    expect(ProjectStatus::ON_HOLD->isOperational())->toBeTrue();
    expect(ProjectStatus::DRAFT->isOperational())->toBeFalse();
    expect(ProjectStatus::COMPLETED->isOperational())->toBeFalse();
});

// --- isInWorkflow ---

test('SUBMITTED, UNDER_REVIEW, APPROVED et REJECTED sont dans le workflow', function () {
    expect(ProjectStatus::SUBMITTED->isInWorkflow())->toBeTrue();
    expect(ProjectStatus::UNDER_REVIEW->isInWorkflow())->toBeTrue();
    expect(ProjectStatus::APPROVED->isInWorkflow())->toBeTrue();
    expect(ProjectStatus::REJECTED->isInWorkflow())->toBeTrue();
});

test('DRAFT et ACTIVE ne sont pas dans le workflow', function () {
    expect(ProjectStatus::DRAFT->isInWorkflow())->toBeFalse();
    expect(ProjectStatus::ACTIVE->isInWorkflow())->toBeFalse();
});
