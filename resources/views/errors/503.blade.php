@extends('errors::minimal')

@section('title', __('errors.503'))
@section('code', '503')
@section('message', $exception->getMessage() ? $exception->getMessage() : __('errors.503'))
